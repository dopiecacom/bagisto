<div id="paczkomaty_locations_dropdown" class="row col-12 form-field" style="display: none">
    <label for="paczkomaty_locations_search" class="form-label">Wyszukaj docelowy paczkomat</label>
    <input list="paczkomaty_locations"
           id="paczkomaty_locations_search"
           class="form-control"
           placeholder="{{ sprintf('[%s] %s %s %s', $lockers[0]->name, $lockers[0]->address, $lockers[0]->post_code, $lockers[0]->city) }}"
           {{-- Modify below to work with all shipping methods
           v-validate="'required'"
           @change="methodSelected()" --}}/>
    <datalist id="paczkomaty_locations">
        @foreach ($lockers as $locker)
            <option
                value="{{ sprintf('[%s] %s %s %s', $locker->name, $locker->address, $locker->post_code, $locker->city) }}">
            </option>
        @endforeach
    </datalist>
    {{--
    <select class="control styled-select" type="text" name="paczkomat_location">
        @foreach ($lockers as $locker)
            <option value="{{ $locker->getAttribute('name') }}">
                {{ sprintf('[%s] %s', $locker->getAttribute('name'), $locker->getAttribute('address')) }}
            </option>
        @endforeach
    </select>
    --}}
</div>
