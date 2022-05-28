<?php
    namespace App\Http\Controllers;

    use App\Models\Fridge;
    use App\Http\Requests\StoreFridgeRequest;
    use App\Http\Requests\UpdateFridgeRequest;
    use Illuminate\Http\Request;
    use Exception;

    class FridgeController extends Controller
    {

        public function index()
        {
            return view('fridges.index', [
                'fridges' => Fridge::with('products.category')->get()
            ]);
        }

        public function indexOwn(Request $request)
        {
            $fridges = $request->user()->fridges()->with('products.category')->get()/*->sortBy('products.expiration_date', 'desc')*/;
            foreach($fridges as $fridge) {
                $fridge->products = $fridge->products->sortBy('expiration_date')->values()->all();
            }
            return view('fridges.index', [
                'fridges' => $fridges
            ]);
        }

        public function create()
        {
            return view('fridges.create');
        }

        public function store(StoreFridgeRequest $request)
        {
            $fridge=Fridge::create($request->validated() + ['owner_id' => $request->user()->id]);
            $fridge->save();
            $request->user()->fridges()->attach($fridge->id, ['is_manager' => 1]);

            return redirect()->route('myfridges.indexOwn');
        }

        public function show(Fridge $fridge)
        {
            $fridge->products = $fridge->products->sortBy('expiration_date')->values()->all();
            return view('fridges.show', [
                'fridge' => $fridge
            ]);
        }

        public function showOwn(Request $request, Fridge $fridge)
        {
            $fridge->products = $fridge->products->sortBy('expiration_date')->values()->all();
            if($request->user()->isFridgeUser($fridge)) {
                return view('fridges.show', [
                    'fridge' => $fridge
                ]);
            }
            abort(403, 'Access denied');
        }

        public function edit(Fridge $fridge)
        {
            return view('fridges.edit', [
                'fridge' => $fridge
            ]);
        }

        public function editOwn(Request $request, Fridge $fridge)
        {
            if($request->user()->isPermittedToManage($fridge)) {
                return view('fridges.edit', [
                    'fridge' => $fridge
                ]);
            }
            abort(403, 'Access denied');
        }

        public function update(UpdateFridgeRequest $request, Fridge $fridge)
        {
            $fridge->update($request->validated());
            return redirect()->route('fridges.index');
        }

        public function updateOwn(UpdateFridgeRequest $request, Fridge $fridge){
            $fridge->update($request->validated());
            return redirect()->route('myfridges.indexOwn');
            abort(403, 'Access denied');
        }

        public function destroy(Fridge $fridge)
        {
            $fridge->delete();
            return redirect()->route('fridges.index');
        }

        public function destroyOwn(Request $request, Fridge $fridge)
        {
            if($request->user()->isFridgeManager($fridge)){
                foreach ($fridge->users as $user) {
                    $user->fridges()->detach($fridge->id);
                }
                $fridge->delete();
                return redirect()->route('myfridges.indexOwn');
            }
            abort(403, 'Access denied');
        }
    }

?>
