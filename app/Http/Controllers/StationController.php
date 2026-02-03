<?php
namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Station::withCount([
            'tripsFrom as trips_from_count',
            'tripsTo as trips_to_count',
        ]);

        // Filter by search term (name or city)
        $query->when($request->search, function ($q, $search) {
            // عشان يبحث باللغة الحالية
            $locale = app()->getLocale();

            $q->where(function ($sub) use ($search, $locale) {
                $sub->where("name->$locale", 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        });

        $stations = $query->orderByDesc('id')->paginate(10);

        return view('admin.stations.index', compact('stations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $station = new Station();
        return view('admin.stations.create', compact('station'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // 'name' => 'required',
            'name.*' => 'required|string',
            'city'   => 'required',
        ]);
        Station::create($request->only('name', 'city'));
        // Station::create([
        //     'name' => $request->name,
        //     'city' => $request->city,
        // ]);

        return redirect()->route('admin.stations.index')->with('msg', __('driver.station_created'))->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $station = Station::findOrFail($id);
        if (! $station) {
            return redirect()->route('admin.stations.index');
        }
        return view('admin.stations.show', compact('station'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $station = Station::find($id);
        return view('admin.stations.edit', compact('station'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            // 'name' => 'required',
            'name.*' => 'required|string',
            'city'   => 'required',
        ]);

        $station = Station::findOrFail($id);
        // الافضل عشان قصة العربي والانجليزي
        $station->update($request->only('name', 'city'));

        // $station->update([
        //     'name' => $request->name,
        //     'city' => $request->city,
        // ]);

        return redirect()->route('admin.stations.index')->with('msg', __('driver.station_updated'))->with('type', 'info');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $station = Station::withCount(['tripsFrom', 'tripsTo'])->findOrFail($id);

        // إذا المحطة مرتبطة بأي رحلات (من أو إلى)
        if (($station->trips_from_count + $station->trips_to_count) > 0) {
            return redirect()
                ->route('admin.stations.index')
                ->with('msg', __('driver.station_delete_has_trips'))
                ->with('type', 'danger');
        }

        $station->delete();

        return redirect()
            ->route('admin.stations.index')
            ->with('msg', __('driver.station_deleted'))
            ->with('type', 'warning');
    }

    public function trash()
    {
        $stations = Station::onlyTrashed()->orderByDesc('id')->get();
        return view('admin.stations.trash', compact('stations'));
    }

    public function restore($id)
    {
        Station::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.stations.trash');
    }

    public function forcedelete($id)
    {
        Station::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.stations.trash');
    }

    public function restore_all()
    {
        Station::onlyTrashed()->restore();
        return redirect()->route('admin.stations.trash');
    }

    public function delete_all()
    {
        Station::onlyTrashed()->forceDelete();
        return redirect()->route('admin.stations.trash');
    }
}
