<?php
    namespace App\Http\Controllers;

    use App\Models\Fridge;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Http\Request;
    use Exception;

    class FridgeController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
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

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */

        public function create()
        {
            return view('fridges.create');
        }
        /**
         * Store a newly created Fridge in storage.
         * Assign creator to the owner of the fridge.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         *
         */
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $fridge = new Fridge();
            $fridge->name = $request->name;
            $fridge->owner_id = Auth::user()->id;
            $fridge->save();

            Auth::user()->fridges()->attach($fridge->id, ['is_manager' => 1]);

            return redirect()->route('myfridges.indexOwn');
        }

        /**
         * Display the specified resource.
         *
         * @param  \App\Models\Fridge  $fridge
         * @return \Illuminate\Http\Response
         */

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

        /**
         * Show the form for editing the specified resource.
         *
         * @param  \App\Models\Fridge  $fridge
         * @return \Illuminate\Http\Response
         */
        public function edit(Fridge $fridge)
        {
            return view('fridges.edit', [
                'fridge' => $fridge
            ]);
        }
        /**
         * Show the form for editing the specified resource.
         * Can be requested just by owner
         * @param  \App\Models\Fridge  $fridge
         * @return \Illuminate\Http\Response
         */
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

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \App\Models\Fridge  $fridge
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, Fridge $fridge)
        {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $fridge->update($request->all());

            return redirect()->route('fridges.index');
        }
        /**
         * Update the specified resource in storage.
         * Can be requested just by owner
         * @param  \Illuminate\Http\Request  $request
         * @param  \App\Models\Fridge  $fridge
         * @return \Illuminate\Http\Response
         */
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

        /**
         * Remove the specified resource from storage.
         *
         * @param  \App\Models\Fridge  $fridge
         * @return \Illuminate\Http\Response
         */
        public function destroy(Fridge $fridge)
        {
            $users = $fridge->users;
            foreach ($users as $user) {
                $user->fridges()->detach($fridge->id);
            }
            $fridge->delete();


            return redirect()->route('fridges.index');
        }
        /**
         * Remove the specified resource from storage.
         * Can be requested just by owner
         * @param  \App\Models\Fridge  $fridge
         * @return \Illuminate\Http\Response
         */
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
