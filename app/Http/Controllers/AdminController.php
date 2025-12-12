<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Calendar;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Comprueba si el usuario es admin; si no, aborta 403
     */
    private function checkAdmin()
    {
        if (!Auth::check() || ! (Auth::user()->is_admin ?? false)) {
            abort(403, 'Acceso denegado.');
        }
    }

    /**
     * Dashboard del admin (pantalla con dos botones: Usuarios / Calendarios)
     */
    public function dashboard()
    {
        $this->checkAdmin();
        return view('admin.dashboard');
    }

    // -------------------------
    // USERS
    // -------------------------
    public function usersIndex()
    {
        $this->checkAdmin();
        $users = User::orderBy('id', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function editUser(User $user)
    {
        $this->checkAdmin();
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $this->checkAdmin();

        // Validación
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6',
            'is_admin' => 'nullable',
        ]);

        $user->name = $data['name'];
        $user->apellido = $data['apellido'] ?? $user->apellido;
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // checkbox
        $user->is_admin = $request->has('is_admin') ? 1 : 0;

        $user->save();

        // ⬅️ MODIFICADO — volver al listado con mensaje de éxito
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroyUser(User $user)
    {
        $this->checkAdmin();

        if ($user->email === 'admin@admin.com') {
            return back()->with('error', 'No se puede eliminar al usuario administrador principal.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta mientras estás logueado.');
        }

        try {
            $user->delete();
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo eliminar el usuario: ' . $e->getMessage());
        }

        return back()->with('success', 'Usuario eliminado correctamente.');
    }

    // -------------------------
    // CALENDARS
    // -------------------------
    public function calendarsIndex()
    {
        $this->checkAdmin();
        $calendars = Calendar::with('flat')->orderBy('id', 'desc')->paginate(20);

        return view('admin.calendars_admin.index', compact('calendars'));
    }

    public function editCalendar(Calendar $calendar)
    {
        $this->checkAdmin();
        return view('admin.calendars_admin.edit', compact('calendar'));
    }

    public function updateCalendar(Request $request, Calendar $calendar)
{
    $this->checkAdmin();

    $data = $request->validate([
        'name'    => 'required|string|max:255',
        'year'    => 'required|integer',
        'month'   => 'required|integer|min:1|max:12',
        'flat_id' => 'nullable|integer|exists:flats,id',
        'versiones' => 'nullable|integer',
    ]);

    // Construimos month_start en formato YYYY-MM-01 (primer día del mes)
    $year = (int) $data['year'];
    $month = (int) $data['month'];
    // Aseguramos dos dígitos en el mes
    $month_padded = str_pad($month, 2, '0', STR_PAD_LEFT);
    $month_start = "{$year}-{$month_padded}-01";

    // Actualizamos solo los campos que existen en la tabla:
    $calendar->name = $data['name'];
    $calendar->flat_id = $data['flat_id'] ?? $calendar->flat_id;
    $calendar->month_start = $month_start;

    if (isset($data['versiones'])) {
        $calendar->versiones = $data['versiones'];
    }

    $calendar->save();

    return redirect()->route('admin.calendars.index')
        ->with('success', 'Calendario actualizado correctamente.');
}



    public function destroyCalendar(Calendar $calendar)
    {
        $this->checkAdmin();

        try {
            $calendar->delete();
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo eliminar el calendario: ' . $e->getMessage());
        }

        return back()->with('success', 'Calendario eliminado correctamente.');
    }
}
