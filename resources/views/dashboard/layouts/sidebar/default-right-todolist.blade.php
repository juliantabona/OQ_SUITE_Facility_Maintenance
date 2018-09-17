<div id="right-sidebar" class="settings-panel">
    <i class="settings-close mdi mdi-close"></i>
    <ul class="nav nav-tabs" id="setting-panel" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="todo-tab" data-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true"
                aria-selected="false">TO DO LIST</a>
        </li>
    </ul>
    <div class="tab-content" id="setting-content">
        <div class="tab-pane fade scroll-wrapper ps ps--theme_default ps--active-y active show" id="todo-section" role="tabpanel"
            aria-labelledby="todo-section" data-ps-id="6a272c4c-9f80-590a-f99c-4fc583bcad47">
            <div class="add-items d-flex px-3 mb-0">
                <form class="form w-100">
                    <div class="form-group d-flex">
                        <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                        <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
                    </div>
                </form>
            </div>
            <div class="list-wrapper px-3">
                <ul class="d-flex flex-column-reverse todo-list">
                    <li>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="checkbox" type="checkbox"> Resolve all the low priority tickets due today
                                <i class="input-helper"></i>
                            </label>
                        </div>
                        <i class="remove mdi mdi-close-circle-outline"></i>
                    </li>
                    <li class="completed">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="checkbox" type="checkbox" checked=""> Schedule meeting with Lobatse borehole superviser
                                <i class="input-helper"></i>
                            </label>
                        </div>
                        <i class="remove mdi mdi-close-circle-outline"></i>
                    </li>
                    <li class="completed">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="checkbox" type="checkbox" checked=""> Follow up on MOH air-condition installation with contractor
                                <i class="input-helper"></i>
                            </label>
                        </div>
                        <i class="remove mdi mdi-close-circle-outline"></i>
                    </li>
                </ul>
            </div>

            <button type="submit" class="btn btn-primary mt-4 mr-1 float-right">Save Changes</button>

        </div>
        <!-- To do section tab ends -->
    </div>
</div>