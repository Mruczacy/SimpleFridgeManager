<?php
    namespace App\Http\Controllers;

    use App\Models\Fridge;
    use App\Http\Requests\ValidateFridgeRequest;
    use Illuminate\Support\Facades\Auth;
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

        public function indexOwn()
        {
            $fridges = Auth::user()->fridges()->with('products.category')->get()/*->sortBy('products.expiration_date', 'desc')*/;
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

        public function store(ValidateFridgeRequest $request)
        {
            $fridge=Fridge::create($request->validated() + ['owner_id' => Auth::id()]);
            $fridge->save();
            Auth::user()->fridges()->attach($fridge->id, ['is_manager' => 1]);

            return redirect()->route('myfridges.indexOwn');
        }

        public function show(Fridge $fridge)
        {
            $fridge->products = $fridge->products->sortBy('expiration_date')->values()->all();
            return view('fridges.show', [
                'fridge' => $fridge
            ]);
        }

        public function showOwn(Fridge $fridge)
        {
            $fridge->products = $fridge->products->sortBy('expiration_date')->values()->all();
            if(Auth::user()->isFridgeUser($fridge)) {
                return view('fridges.show', [
                    'fridge' => $fridge
                ]);
            } else {
                abort(403, 'Access denied');
            }
        }

        public function edit(Fridge $fridge)
        {
            return view('fridges.edit', [
                'fridge' => $fridge
            ]);
        }

        public function editOwn(Fridge $fridge)
        {
            if(Auth::user()->isPermittedToManage($fridge)) {
                return view('fridges.edit', [
                    'fridge' => $fridge
                ]);
            } else {
                abort(403, 'Access denied');
            }
        }

        public function update(ValidateFridgeRequest $request, Fridge $fridge)
        {
            $fridge->update($request->validated());
            return redirect()->route('fridges.index');
        }

        public function updateOwn(ValidateFridgeRequest $request, Fridge $fridge){
            if(Auth::user()->isPermittedToManage($fridge)){
                $fridge->update($request->validated());
                return redirect()->route('myfridges.indexOwn');
            } else {
                abort(403, 'Access denied');
            }
        }

        public function destroy(Fridge $fridge)
        {
            $fridge->delete();
            return redirect()->route('fridges.index');
        }

        public function destroyOwn(Fridge $fridge)
        {
            if(Auth::user()->isFridgeManager($fridge)){
                foreach ($fridge->users as $user) {
                    $user->fridges()->detach($fridge->id);
                }
                $fridge->delete();
                return redirect()->route('myfridges.indexOwn');
            } else {
                abort(403, 'Access denied');
            }
        }
    }

?>
