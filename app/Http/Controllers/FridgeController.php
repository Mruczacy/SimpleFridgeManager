<?php
    namespace App\Http\Controllers;

    use App\Models\Fridge;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Http\Request;

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
                'fridges' => Fridge::paginate(3)
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

        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $fridge = new Fridge();
            $fridge->name = $request->name;
            $fridge->save();

            return redirect()->route('fridges.index');
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
            if(Auth::user()->id == $fridge->user_id) {
                return view('fridges.edit', [
                    'fridge' => $fridge
                ]);
            }
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \App\Models\Fridge  $fridge
         * @return \Illuminate\Http\Response
         */
        public function update()
        {
            $request->validate([
                'name' => 'required',
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
            if(Auth::user()->id == $fridge->id){
                $request->validate([
                    'name' => 'required',
                ]);

                $fridge->update($request->all());

                return redirect()->route('home');
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
            $fridge->delete();
            return redirect()->route('home');
        }
    }

?>
