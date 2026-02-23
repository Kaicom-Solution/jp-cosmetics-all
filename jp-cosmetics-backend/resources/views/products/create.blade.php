@extends('master')

@section('contents')

<div class="rounded shadow-sm">
    <div class="p-3 d-flex justify-content-between align-items-center rounded-top" style="background-color: rgb(119, 82, 125); color:#ffffff">
        <h3 class="fs-5">Create Product</h3>
        <a href="{{ route('product.list') }}" class="btn btn-outline-light btn-sm">← Back to Products</a>
    </div>
    {{-- any error --}}
    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}
    <form id="productForm" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded-bottom">
        @csrf

        <div class="row g-4">
            <!-- Primary Image -->
            <div class="col-md-3 text-center">
                <div class="position-relative border rounded bg-light d-flex align-items-center justify-content-center" style="min-height:240px;">
                    <img id="primaryImagePreview" src="{{ asset('imagePH.png') }}" alt="Primary Image" class="w-100 h-100 object-fit-cover rounded"/>
                    <button type="button" id="removePrimaryImage" class="btn btn-sm btn-light bg-white border position-absolute top-0 end-0 rounded-circle">×</button>
                </div>
                <input type="file" accept="image/*" id="primaryImageInput" name="primary_image" class="d-none" />
                <p class="text-muted bg-green-300">Recommended Size: (337.5 * 256)px Ratio</p>
                <label for="primaryImageInput" class="btn btn-dark mt-3 w-100">Choose Primary Image</label>
                @error('primary_image') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        
            <!-- Fields -->
            <div class="col-md-9 mt-10">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Product Name</label>
                        <input required type="text" name="name" id="name" class="form-control" placeholder="Enter Product Name" value="{{ old('name') }}" />
                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
        
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select required name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
        
                    <div class="col-md-4">
                        <label class="form-label">Brand (optional)</label>
                        <select name="brand_id" class="form-select">
                            <option value="">None</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
        
                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <label class="form-label">Product Type</label>
                        <select name="product_type" id="product_type" class="form-select">
                            <option value="single" {{ old('product_type') == 'single' ? 'selected' : '' }}>Single</option>
                            <option value="configurable" {{ old('product_type') == 'configurable' ? 'selected' : '' }}>Configurable</option>
                            <option value="digital" {{ old('product_type') == 'digital' ? 'selected' : '' }}>Digital</option>
                        </select>
                        @error('product_type') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
        
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                        @error('status') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
        
                    <input type="hidden" name="slug" id="slug" value="{{ old('slug') }}">
                </div>
        
                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="3" placeholder="Enter a short description...">{{ old('short_description') }}</textarea>
                        @error('short_description') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Long Description</label>
                        <textarea name="long_description" class="form-control" rows="3" placeholder="Enter a long description...">{{ old('long_description') }}</textarea>
                        @error('long_description') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Ingredients</label>
                        <textarea 
                            name="ingredients" 
                            class="form-control" 
                            rows="4" 
                            placeholder="Enter product ingredients...">{{ old('ingredients') }}</textarea>
                        @error('ingredients') 
                            <div class="text-danger">{{ $message }}</div> 
                        @enderror
                    </div>
                
                    <div class="col-md-6">
                        <label class="form-label">How To Use</label>
                        <textarea 
                            name="how_to_use" 
                            class="form-control" 
                            rows="4" 
                            placeholder="Enter usage instructions...">{{ old('how_to_use') }}</textarea>
                        @error('how_to_use') 
                            <div class="text-danger">{{ $message }}</div> 
                        @enderror
                    </div>
                </div>
                
                
            </div>
        </div>
        
        <!-- Attributes -->
        <div class="mt-4" id="attribute_div">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="m-0">Product Attributes</h5>
                <button class="btn btn-sm btn-outline-primary" type="button" id="addAttributeBtn">+ Add Attribute</button>
            </div>
            <small class="text-muted d-block mt-1">
                For <strong>configurable</strong> products, add one or more attribute rows (e.g., Size=L, Color=Red).
            </small>

            @php
            $oldAttrs = old('attributes', []);
            $productType = old('product_type', 'single'); 
        @endphp
        
        <div id="attributesContainer" class="mt-3">
        
            {{-- SHOW DEFAULT SINGLE ATTRIBUTE ONLY IF:  
                1. Product type is "single", AND
                2. There are no old attributes (no validation error)
            --}}
            @if($productType === 'single' && empty($oldAttrs))
                <div class="attr-row border rounded p-3 mb-3" data-attr-row="0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Default Attribute</strong>
                    </div>
        
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Attribute Name</label>
                            <input type="text" name="attributes[0][attribute_name]" class="form-control"
                                placeholder="e.g., Size, Color" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Attribute Value</label>
                            <input type="text" name="attributes[0][attribute_value]" class="form-control"
                                placeholder="e.g., L, Red" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Unit Price</label>
                            <input type="number" step="0.01" name="attributes[0][unit_price]" class="form-control"
                                placeholder="1299.00" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Stock</label>
                            <input type="number" name="attributes[0][stock]" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Min Order</label>
                            <input type="number" name="attributes[0][min_order]" class="form-control" value="1">
                        </div>
                    </div>
        
                    <div class="row g-3 mt-1">
                        <div class="col-md-2">
                            <label class="form-label">Max Order</label>
                            <input type="number" name="attributes[0][max_order]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Discount Type</label>
                            <select name="attributes[0][discount_type]" class="form-select">
                                <option value="">None</option>
                                <option value="fixed">Fixed</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Discount Amount</label>
                            <input type="number" step="0.01" name="attributes[0][discount_amount]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Active?</label>
                            <select name="attributes[0][status]" class="form-select">
                                <option value="1" selected>Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="col-md-2" style="position: relative; top:40px">
                            <label class="form-label" style="position: relative; top:-5px">Default?</label>
                            <input type="checkbox" class="def" id="is_default_checkbox"
                                name="attributes[0][is_default]" style="height:20px;width:20px">
                        </div>
                    </div>
        
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label">Attribute Images</label>
                            <input type="file" accept="image/*" multiple class="form-control attr-image-input"
                                name="attributes[0][attribute_images][]">
                            <div class="attr-images-preview mt-2"></div>
                        </div>
                    </div>
                </div>
            @endif
        
            {{-- RENDER OLD ATTRIBUTES ONLY WHEN VALIDATION FAILS  
                 (This applies to BOTH single + configurable products)
            --}}
            @if(!empty($oldAttrs))
                @foreach($oldAttrs as $idx => $attr)
                    <div class="attr-row border rounded p-3 mb-3" data-attr-row="{{ $idx }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Attribute #{{ $idx + 1 }}</strong>
                            <button class="btn btn-sm btn-outline-danger btn-remove-attr" type="button">Remove</button>
                        </div>
        
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Attribute Name</label>
                                <input type="text" name="attributes[{{ $idx }}][attribute_name]" class="form-control"
                                    value="{{ $attr['attribute_name'] ?? '' }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Attribute Value</label>
                                <input type="text" name="attributes[{{ $idx }}][attribute_value]" class="form-control"
                                    value="{{ $attr['attribute_value'] ?? '' }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Unit Price</label>
                                <input type="number" step="0.01" name="attributes[{{ $idx }}][unit_price]"
                                    class="form-control" value="{{ $attr['unit_price'] ?? '' }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Stock</label>
                                <input type="number" name="attributes[{{ $idx }}][stock]" class="form-control"
                                    value="{{ $attr['stock'] ?? 0 }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Min Order</label>
                                <input type="number" name="attributes[{{ $idx }}][min_order]" class="form-control"
                                    value="{{ $attr['min_order'] ?? 1 }}">
                            </div>
                        </div>
        
                        <div class="row g-3 mt-1">
                            <div class="col-md-2">
                                <label class="form-label">Max Order</label>
                                <input type="number" name="attributes[{{ $idx }}][max_order]" class="form-control"
                                    value="{{ $attr['max_order'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Discount Type</label>
                                <select name="attributes[{{ $idx }}][discount_type]" class="form-select">
                                    <option value="">None</option>
                                    <option value="fixed" {{ (isset($attr['discount_type']) && $attr['discount_type'] == 'fixed') ? 'selected' : '' }}>Fixed</option>
                                    <option value="percentage" {{ (isset($attr['discount_type']) && $attr['discount_type'] == 'percentage') ? 'selected' : '' }}>Percentage</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Discount Amount</label>
                                <input type="number" step="0.01" name="attributes[{{ $idx }}][discount_amount]"
                                    class="form-control" value="{{ $attr['discount_amount'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Active?</label>
                                <select name="attributes[{{ $idx }}][status]" class="form-select">
                                    <option value="1" {{ ($attr['status'] ?? 1) == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ ($attr['status'] ?? 1) == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-md-2" style="position: relative; top:40px">
                                <label class="form-label" style="position: relative; top:-5px">Default?</label>
                                <input type="checkbox" class="def" name="attributes[{{ $idx }}][is_default]"
                                    {{ isset($attr['is_default']) ? 'checked' : '' }}
                                    style="height:20px;width:20px">
                            </div>
                        </div>
        
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Attribute Images</label>
                                <input type="file" accept="image/*" multiple class="form-control attr-image-input"
                                    name="attributes[{{ $idx }}][attribute_images][]">
                                <div class="attr-images-preview mt-2"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        
        </div>
        
        </div>

        @hasPermission('products.store')
        <div class="row g-3 mt-4">
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary px-4">Save Product</button>
            </div>
        </div>
        @endHasPermission
    </form>
</div>

<!-- JS -->
<script>
    // Primary image preview/remove
    const primInput = document.getElementById('primaryImageInput');
    const primImg   = document.getElementById('primaryImagePreview');
    document.getElementById('primaryImageInput').addEventListener('change', e => {
        const f = e.target.files[0]; if (!f) return;
        const r = new FileReader(); r.onload = ev => primImg.src = ev.target.result; r.readAsDataURL(f);
    });
    document.getElementById('removePrimaryImage').addEventListener('click', () => {
        primImg.src = "{{ asset('imagePH.png') }}";
        primInput.value = "";
    });

    // Slugify from name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const slugify = s => s.toString().trim().toLowerCase()
        .replace(/[\s_]+/g,'-').replace(/[^a-z0-9\-]/g,'')
        .replace(/\-+/g,'-').replace(/^\-+|\-+$/g,'');
    nameInput.addEventListener('input', () => {
        if (!slugInput.dataset.touched || slugInput.dataset.touched !== '1') {
            slugInput.value = slugify(nameInput.value);
        }
    });

    // Dynamic attribute rows
    const container = document.getElementById('attributesContainer');
    let idx = Array.from(container.querySelectorAll('[data-attr-row]'))
        .reduce((m, el) => Math.max(m, parseInt(el.getAttribute('data-attr-row'),10)), -1);
    if (isNaN(idx)) idx = 0;

    // document.getElementById('addAttributeBtn').addEventListener('click', () => {
    //     idx++;
    //     container.insertAdjacentHTML('beforeend', rowHtml(idx));
    // });

    container.addEventListener('click', e => {
        if (e.target.matches('.btn-remove-attr')) {
            e.preventDefault();
            e.target.closest('.attr-row')?.remove();
        }
    });

    document.getElementById('addAttributeBtn').addEventListener('click', () => {
        idx++;
        container.insertAdjacentHTML('beforeend', rowHtml(idx));
        initAttrImageManager(container.lastElementChild); // ← এই লাইন ADD করুন
    });

    // container.addEventListener('change', e => {
    //     if (e.target.matches('.attr-image-input')) {
    //         const previewBox = e.target.closest('.attr-row').querySelector('.attr-images-preview');
    //         previewBox.innerHTML = '';
    //         Array.from(e.target.files).forEach(f => {
    //             const r = new FileReader();
    //             r.onload = ev => {
    //                 const img = document.createElement('img');
    //                 img.src = ev.target.result;
    //                 img.className = 'rounded border me-2 mb-2';
    //                 img.style.width='56px'; img.style.height='56px'; img.style.objectFit='cover';
    //                 previewBox.appendChild(img);
    //             };
    //             r.readAsDataURL(f);
    //         });
    //     }
    // });

    function rowHtml(i){
        return `
        <div class="attr-row border rounded p-3 mb-3" data-attr-row="${i}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong>Attribute #${i+1}</strong>
                <button class="btn btn-sm btn-outline-danger btn-remove-attr" type="button">Remove</button>
            </div>

            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Attribute Name</label>
                    <input type="text" name="attributes[${i}][attribute_name]" class="form-control" placeholder="e.g., Size, Color" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Attribute Value</label>
                    <input type="text" name="attributes[${i}][attribute_value]" class="form-control" placeholder="e.g., L, Red" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit Price</label>
                    <input type="number" step="0.01" name="attributes[${i}][unit_price]" class="form-control" placeholder="1299.00" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Stock</label>
                    <input type="number" name="attributes[${i}][stock]" class="form-control" value="0" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Min Order</label>
                    <input type="number" name="attributes[${i}][min_order]" class="form-control" value="1">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-2">
                    <label class="form-label">Max Order</label>
                    <input type="number" name="attributes[${i}][max_order]" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Discount Type</label>
                    <select name="attributes[${i}][discount_type]" class="form-select">
                        <option value="">None</option>
                        <option value="fixed">Fixed</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Discount Amount</label>
                    <input type="number" step="0.01" name="attributes[${i}][discount_amount]" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Active?</label>
                    <select name="attributes[${i}][status]" class="form-select">
                        <option value="1" selected>Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                 <div class="col-md-2" style="position: relative; top:40px">
                    <label class="form-label" style="position: relative; top:-5px">Default?</label>
                    <input type="checkbox" class="def" name="attributes[${i}][is_default]" style="height:20px; width:20px">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label class="form-label">Attribute Images</label>
                    <input type="file" accept="image/*" multiple class="form-control attr-image-input" name="attributes[${i}][attribute_images][]">
                    <div class="attr-images-preview mt-2"></div>
                </div>
            </div>
        </div>`;
    }

    document.getElementById('product_type').addEventListener('change', function() {
    const addButton = document.getElementById('addAttributeBtn');
    const attributeDiv = document.getElementById('attribute_div');
    const productType = this.value;
    const defaultCheckbox = document.getElementById('is_default_checkbox');

        if (productType === 'configurable') {
            addButton.style.display = 'inline-block';
            attributeDiv.style.display = 'block';
            if (defaultCheckbox) {
                defaultCheckbox.checked = false;
                defaultCheckbox.disabled = false;
            }
        } else if (productType === 'single') {
            addButton.style.display = 'none';
            attributeDiv.style.display = 'block';
            if (defaultCheckbox) {
                defaultCheckbox.checked = true;
                defaultCheckbox.disabled = false;
            }
        } else { // digital
            addButton.style.display = 'none';
            attributeDiv.style.display = 'none';
            if (defaultCheckbox) {
                defaultCheckbox.checked = false;
                defaultCheckbox.disabled = true;
            }
        }
    });


    const productType = document.getElementById('product_type').value;
    if (productType === 'configurable') {
        document.getElementById('addAttributeBtn').style.display = 'inline-block';
    } else if (productType === 'single') {
        document.getElementById('addAttributeBtn').style.display = 'none';
        document.getElementById('attribute_div').style.display = 'block';
    }

   // HTML: <input type="checkbox" class="def" ...>

    document.addEventListener('change', function (e) {
    if (!e.target.matches('input.def[type="checkbox"]')) return;

    document.querySelectorAll('input.def[type="checkbox"]').forEach(cb => {
        if (cb !== e.target) cb.checked = false;
    });
    });

    (function initDefaultCheckbox() {
        const productType = document.getElementById('product_type').value;
        const defaultCheckbox = document.getElementById('is_default_checkbox');

        if (!defaultCheckbox) return;

        if (productType === 'single') {
            defaultCheckbox.checked = true;
            defaultCheckbox.disabled = false;
        } else if (productType === 'configurable') {
            defaultCheckbox.disabled = false;
        } else {
            defaultCheckbox.checked = false;
            defaultCheckbox.disabled = true;
        }
    })();


    // ============================================
    // ATTRIBUTE IMAGE MANAGER
    // ============================================
    const attrImageStore = {};

    function getAttrIndex(attrRow) {
        return attrRow.getAttribute('data-attr-row');
    }

    function renderAttrImages(attrRow) {
        const idx = getAttrIndex(attrRow);
        const files = attrImageStore[idx] || [];
        const previewBox = attrRow.querySelector('.attr-images-preview');

        previewBox.innerHTML = '';

        files.forEach((file, fileIdx) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'attr-img-wrapper';
            wrapper.style.cssText = 'position:relative; display:inline-block; margin:4px;';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'rounded border';
            img.style.cssText = 'width:64px; height:64px; object-fit:cover; display:block;';

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.innerHTML = '&times;';
            removeBtn.style.cssText = `
                position:absolute; top:-6px; right:-6px;
                width:20px; height:20px;
                background:#dc3545; color:#fff; border:none;
                border-radius:50%; font-size:13px; line-height:1;
                cursor:pointer; display:none; align-items:center;
                justify-content:center; padding:0; z-index:10;
            `;
            removeBtn.addEventListener('click', () => {
                attrImageStore[idx].splice(fileIdx, 1);
                syncFileInput(attrRow);
                renderAttrImages(attrRow);
            });

            wrapper.addEventListener('mouseenter', () => removeBtn.style.display = 'flex');
            wrapper.addEventListener('mouseleave', () => removeBtn.style.display = 'none');

            wrapper.appendChild(img);
            wrapper.appendChild(removeBtn);
            previewBox.appendChild(wrapper);
        });

        // + button
        const addMoreBtn = document.createElement('div');
        addMoreBtn.style.cssText = `
            display:inline-flex; align-items:center; justify-content:center;
            width:64px; height:64px; border:2px dashed #adb5bd;
            border-radius:6px; cursor:pointer; font-size:28px; color:#6c757d;
            margin:4px; vertical-align:top; transition: border-color .15s, color .15s;
            user-select:none;
        `;
        addMoreBtn.title = 'Add more images';
        addMoreBtn.textContent = '+';
        addMoreBtn.addEventListener('mouseenter', () => {
            addMoreBtn.style.borderColor = '#0d6efd';
            addMoreBtn.style.color = '#0d6efd';
        });
        addMoreBtn.addEventListener('mouseleave', () => {
            addMoreBtn.style.borderColor = '#adb5bd';
            addMoreBtn.style.color = '#6c757d';
        });
        addMoreBtn.addEventListener('click', () => {
            const tempInput = document.createElement('input');
            tempInput.type = 'file';
            tempInput.accept = 'image/*';
            tempInput.multiple = true;
            tempInput.addEventListener('change', () => {
                if (!attrImageStore[idx]) attrImageStore[idx] = [];
                Array.from(tempInput.files).forEach(f => attrImageStore[idx].push(f));
                syncFileInput(attrRow);
                renderAttrImages(attrRow);
            });
            tempInput.click();
        });

        previewBox.appendChild(addMoreBtn);
    }

    function syncFileInput(attrRow) {
        const idx = getAttrIndex(attrRow);
        const files = attrImageStore[idx] || [];
        const fileInput = attrRow.querySelector('.attr-image-input');
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
    }

    function initAttrImageManager(attrRow) {
        const idx = getAttrIndex(attrRow);
        if (!attrImageStore[idx]) attrImageStore[idx] = [];
        const fileInput = attrRow.querySelector('.attr-image-input');
        fileInput.style.display = 'none'; // hide original input, use our UI
        renderAttrImages(attrRow);
    }

    // Init all existing rows on page load
    document.querySelectorAll('.attr-row').forEach(row => initAttrImageManager(row));


    // ============================================
    // CONFIGURABLE DEFAULT ATTRIBUTE VALIDATION
    // ============================================

    document.getElementById('productForm').addEventListener('submit', function(e) {

        const productType = document.getElementById('product_type').value;

        if (productType === 'configurable') {

            const defaultChecked = document.querySelectorAll('input.def[type="checkbox"]:checked');

            if (defaultChecked.length === 0) {
                e.preventDefault(); // stop form submit

                alert('For Configurable product, you must select at least one Default Attribute.');

                return false;
            }
        }
    });
    
</script>
<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
['long_description', 'short_description', 'ingredients', 'how_to_use'].forEach(name => {
    tinymce.init({
        selector: `textarea[name="${name}"]`,
        height: 300,
        menubar: false,
        license_key: 'gpl',
        plugins: 'link image lists code table fullscreen',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
    });
});
</script>

@endsection
