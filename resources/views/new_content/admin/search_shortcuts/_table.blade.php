<div class="table-responsive pt-0">
        <table class="table table-hover" id="shortcutsTable">
            <thead>
                <tr>
                    <th>SR NO</th>
                    <x-sortable-header column="icon" label="Icon" />
                    <x-sortable-header column="title" label="Title" />
                    <x-sortable-header column="action_type" label="Action Type" />
                    <x-sortable-header column="target_url" label="Destination" />
                    <x-sortable-header column="click_count" label="Clicks" />
                    <x-sortable-header column="status" label="Status" />
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="sortableList">
                @foreach($searchShortcuts as $shortcut)
                <tr data-id="{{ $shortcut->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($shortcut->icon)
                            <i class="{{ $shortcut->icon }} {{ $shortcut->icon_color }} fs-4"></i>
                        @endif
                    </td>
                    <td><strong>{{ $shortcut->title }}</strong></td>
                    <td><span class="badge bg-label-info">{{ $shortcut->action_type->label() }}</span></td>
                    <td><a href="{{ route('web.search_shortcuts.track', $shortcut->id) }}" target="_blank" class="text-primary small text-truncate d-inline-block" style="max-width:200px;">{{ $shortcut->target_url }}</a></td>
                    <td>
                        <div><span class="fw-bold">{{ number_format($shortcut->click_count) }}</span> Clicks</div>
                        @if($shortcut->last_clicked_at)
                            <small class="text-muted" title="{{ $shortcut->last_clicked_at }}">Last: {{ $shortcut->last_clicked_at->diffForHumans() }}</small>
                        @endif
                    </td>
                    <td>
                        <label class="switch">
                            <input class="switch-input status-toggle" type="checkbox" data-id="{{ $shortcut->id }}" {{ $shortcut->status ? 'checked' : '' }}>
                            <span class="switch-toggle-slider"></span>
                        </label>
                    </td>
                    <td>
                        <a href="{{ route('search-shortcuts.edit', $shortcut->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                        <form action="{{ route('search-shortcuts.destroy', $shortcut->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
                
                @if($searchShortcuts->isEmpty())
                <tr>
                    <td colspan="8" class="text-center">No shortcuts found. Create one!</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
