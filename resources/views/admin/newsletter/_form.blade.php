<form id="newsletter-form" action="{{ $formAction }}" method="POST" class="max-w-xl mx-auto bg-white p-6 rounded shadow space-y-6">
    @csrf
    @if(isset($newsletter))
        @method('PUT')
    @endif

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
               value="{{ old('email', $newsletter->email ?? '') }}" required>
        <p id="email-error" class="mt-1 text-sm text-red-500"></p>
        @error('email')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end">
        <button type="submit"
                class="bg-iri-primary text-white px-4 py-2 rounded hover:bg-iri-secondary">
            {{ isset($newsletter) ? 'Mettre à jour' : 'Enregistrer' }}
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('newsletter-form');
        const email = document.getElementById('email');
        const error = document.getElementById('email-error');

        form.addEventListener('submit', function (e) {
            const value = email.value.trim();
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!value) {
                e.preventDefault();
                error.textContent = "L'adresse email est requise.";
                email.classList.add('border-red-500', 'ring-red-500');
            } else if (!regex.test(value)) {
                e.preventDefault();
                error.textContent = "Format de l'adresse email invalide.";
                email.classList.add('border-red-500', 'ring-red-500');
            } else {
                error.textContent = "";
                email.classList.remove('border-red-500', 'ring-red-500');
            }
        });
    });
</script>


