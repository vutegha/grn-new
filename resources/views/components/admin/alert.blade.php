@if(session('alert'))
    <div class="mb-6 p-4 rounded-lg border border-transparent {{ str_contains(session('alert'), 'text-green') ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                @if(str_contains(session('alert'), 'text-green'))
                    <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                @else
                    <div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-full">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                @endif
            </div>
            <div class="ml-3">
                <p class="{{ str_contains(session('alert'), 'text-green') ? 'text-green-800' : 'text-red-800' }} text-sm font-medium">
                    {!! session('alert') !!}
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" 
                            class="{{ str_contains(session('alert'), 'text-green') ? 'text-green-400 hover:text-green-600' : 'text-red-400 hover:text-red-600' }} inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2">
                        <span class="sr-only">Fermer</span>
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 p-4 rounded-lg border border-red-200 bg-red-50">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
            </div>
            <div class="ml-3 w-full">
                <h3 class="text-sm font-medium text-red-800 mb-2">
                    Erreurs de validation détectées :
                </h3>
                <div class="text-sm text-red-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" 
                            class="text-red-400 hover:text-red-600 inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <span class="sr-only">Fermer</span>
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
