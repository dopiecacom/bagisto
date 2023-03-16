<div id="paczkomaty_locations_dropdown" class="row col-12 form-field" style="display: none">
    <label for="paczkomaty_locations_search" class="form-label">Wyszukaj docelowy paczkomat</label>
    <input list="paczkomaty_locations"
           id="paczkomaty_locations_search"
           class="form-control"
           value="{{ sprintf('[%s] %s %s %s', $lockers[0]->name, $lockers[0]->address, $lockers[0]->post_code, $lockers[0]->city) }}"
           v-validate="'required'"
           @change="methodSelected()"/>
    <datalist id="paczkomaty_locations">
        @foreach ($lockers as $locker)
            <option
                value="{{ sprintf('[%s] %s %s %s', $locker->name, $locker->address, $locker->post_code, $locker->city) }}">
            </option>
        @endforeach
    </datalist>
</div>
