<?php
namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with the base query
        $query = Driver::query()->with('user');

        // Filter by search term (license_number or user's name)
        $query->when($request->search, fn($q) =>
            $q->where(function ($subQ) use ($request) {
                $subQ->where('license_number', 'like', "%{$request->search}%")
                    ->orWhereHas('user', fn($userQ) =>
                        $userQ->where('name', 'like', "%{$request->search}%")
                    );
            })
        );

        // Filter by status (active/inactive)
        $query->when($request->status, fn($q) =>
            $q->whereHas('user', fn($userQ) =>
                $userQ->where('is_active', $request->status == 'active')
            )
        );

        // Order the results
        $query->orderByDesc('id');
        // Paginate
        $drivers = $query->paginate(10);
        // If the request is an AJAX request, return only the table partial
        if ($request->ajax()) {
            return view('admin.drivers.partials.table', compact('drivers'))->render();
        }
        // For regular requests, return the full index view
        return view('admin.drivers.index', compact('drivers'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $driver = new Driver();
        // $users  = User::all();
        $users = User::where('role', 'driver')->get();
        return view('admin.drivers.create', compact('driver', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'             => 'required|unique:drivers,user_id',
            'license_number'      => 'required',
            'license_type'        => 'required',
            'license_expiry_date' => 'required|date',
            'driver_image'        => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        //upload file
        $img_name = null;
        if ($request->hasFile('driver_image')) {
            $img_name = rand() . time() . $request->file('driver_image')->getClientOriginalName();
            $request->file('driver_image')->move(public_path('uploads/drivers'), $img_name);
        }
        $driver = Driver::create(
            [
                // هان يجيبه من لما ينشئ اليوزر
                'user_id'             => $request->user_id,
                'license_number'      => $request->license_number,
                'license_type'        => $request->license_type,
                'license_expiry_date' => $request->license_expiry_date,
                'driver_image'        => $img_name,
            ]
        );

        return redirect()->route('admin.drivers.index')->with('msg', __('driver.driver_created'))->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $driver = Driver::with('user')->findOrFail($id);
        if (! $driver) {
            return redirect()->route('admin.drivers.index');
        }
        return view('admin.drivers.show', compact('driver'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $driver = Driver::findOrFail($id);
        $users  = User::where('role', 'driver')->get();
        return view('admin.drivers.edit', compact('driver', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        $request->validate([
            // 'user_id'             => 'required|unique:drivers,user_id,' . $driver->id,
            'license_number'      => 'required|unique:drivers,license_number,' . $driver->id,
            'license_type'        => 'required',
            'license_expiry_date' => 'required|date',
            'driver_image'        => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $img_name = $driver->driver_image;
        if ($request->hasFile('driver_image')) {
            //upload file
            $img_name = rand() . time() . $request->file('driver_image')->getClientOriginalName();
            $request->file('driver_image')->move(public_path('uploads/drivers'), $img_name);
        }

        $driver->update([
            // 'user_id'             => $request->user_id,
            'license_number'      => $request->license_number,
            'license_type'        => $request->license_type,
            'license_expiry_date' => $request->license_expiry_date,
            'driver_image'        => $img_name,

        ]);

        return redirect()
            ->route('admin.drivers.index')
            ->with('msg', __('driver.driver_updated'))->with('type', 'info');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        if ($driver->receipt_image_path && Storage::disk('uploads/drivers')->exists($driver->receipt_image_path)) {
            Storage::disk('uploads/drivers')->delete($driver->receipt_image_path);
        }
        $driver->delete();
        return redirect()->route('admin.drivers.index')->with('msg', __('driver.driver_deleted'))->with('type', 'warning');
    }
    public function trash()
    {
        $drivers = Driver::onlyTrashed()->orderByDesc('id')->get();
        return view('admin.drivers.trash', compact('drivers'));
    }

    public function restore($id)
    {
        Driver::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.drivers.trash');
    }

    public function forcedelete($id)
    {
        Driver::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.drivers.trash');
    }

    public function restore_all()
    {
        Driver::onlyTrashed()->restore();
        return redirect()->route('admin.drivers.trash');
    }

    public function delete_all()
    {
        Driver::onlyTrashed()->forceDelete();
        return redirect()->route('admin.drivers.trash');
    }

    /**
     * Toggle the active status of a user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(User $user)
    {
        // We only toggle status for users who are drivers
        if ($user->role === 'driver') {
            // Flip the boolean value (true becomes false, false becomes true)
            $user->is_active = ! $user->is_active;
            $user->save();

            $status = $user->is_active ? __('driver.active') : __('driver.inactive');
            return back()->with('msg', __('driver.driver_status_changed', ['status' => $status]))->with('type', 'success');
        }

        return back()->with('msg', __('driver.action_only_for_drivers'))->with('type', 'danger');
    }

}
