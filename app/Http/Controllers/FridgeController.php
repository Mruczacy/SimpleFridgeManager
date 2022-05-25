<?php
    namespace App\Http\Controllers;

    use App\Models\Fridge;
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

            return view('fridges.index', [
                'fridges' => Auth::user()->fridges()->with('products.category')->get()
            ]);
        }

        public function create()
        {
            return view('fridges.create');
        }

        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $fridge = new Fridge();
            $fridge->name = $request->name;
            $fridge->owner_id = Auth::id();
            $fridge->save();

            Auth::user()->fridges()->attach($fridge->id, ['is_manager' => 1]);

            return redirect()->route('myfridges.indexOwn');
        }

        public function show(Fridge $fridge)
        {
            return view('fridges.show', [
                'fridge' => $fridge
            ]);
        }

        public function showOwn(Fridge $fridge)
        {
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

        public function update(Request $request, Fridge $fridge)
        {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $fridge->update($request->all());

            return redirect()->route('fridges.index');
        }

        public function updateOwn(Request $request, Fridge $fridge){
            if(Auth::user()->isPermittedToManage($fridge)){
                $request->validate([
                    'name' => 'required|string|max:255',
                ]);

                $fridge->update($request->all());

                return redirect()->route('myfridges.indexOwn');
            } else {
                abort(403, 'Access denied');
            }
        }

        public function destroy(Fridge $fridge)
        {
            $users = $fridge->users;
            foreach ($users as $user) {
                $user->fridges()->detach($fridge->id);
            }
            $fridge->delete();


            return redirect()->route('fridges.index');
        }

        public function destroyOwn(Fridge $fridge)
        {
            if(Auth::user()->isFridgeManager($fridge)){
                $users = $fridge->users;
                foreach ($users as $user) {
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
