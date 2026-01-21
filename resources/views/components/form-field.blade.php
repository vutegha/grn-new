@props([
    'name' => '',
    'label' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'options' => [],
    'rows' => 4,
    'accept' => '',
    'multiple' => false,
    'help' => ''
])

<div class="space-y-2">
    
    <!-- Label -->
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <!-- Input selon le type -->
    @if($type === 'textarea')
        <textarea 
            name="{{ $name }}" 
            id="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="form-textarea {{ $errors->has($name) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
        >{{ old($name, $value) }}</textarea>
    
    @elseif($type === 'select')
        <select 
            name="{{ $name }}" 
            id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="form-select {{ $errors->has($name) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
        >
            @if(!$required)
                <option value="">-- Sélectionner --</option>
            @endif
            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    
    @elseif($type === 'file')
        <input 
            type="file" 
            name="{{ $name }}" 
            id="{{ $name }}"
            accept="{{ $accept }}"
            {{ $multiple ? 'multiple' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="form-input file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-coral file:text-white hover:file:bg-coral/90 {{ $errors->has($name) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
        >
    
    @elseif($type === 'checkbox')
        <div class="flex items-center">
            <input 
                type="checkbox" 
                name="{{ $name }}" 
                id="{{ $name }}"
                value="1"
                {{ old($name, $value) ? 'checked' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                class="h-4 w-4 text-coral focus:ring-coral border-gray-300 rounded"
            >
            @if($label)
                <label for="{{ $name }}" class="ml-2 block text-sm text-gray-700">
                    {{ $label }}
                </label>
            @endif
        </div>
    
    @elseif($type === 'date')
        <input 
            type="date" 
            name="{{ $name }}" 
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="form-input {{ $errors->has($name) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
        >
    
    @elseif($type === 'email')
        <input 
            type="email" 
            name="{{ $name }}" 
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="form-input {{ $errors->has($name) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
        >
    
    @elseif($type === 'url')
        <input 
            type="url" 
            name="{{ $name }}" 
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="form-input {{ $errors->has($name) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
        >
    
    @else
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="form-input {{ $errors->has($name) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
        >
    @endif

    <!-- Texte d'aide -->
    @if($help)
        <p class="text-xs text-gray-500">{{ $help }}</p>
    @endif

    <!-- Message d'erreur -->
    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror

</div>
