  <div class="table-responsive pt-0">
    <table class="table">
      <thead>
        <tr>
          <th>SR NO</th>
          <x-sortable-header column="name" label="Name" />
          <th>Category</th>
          <th>Start Date</th>
          <x-sortable-header column="city" label="City" />
          <x-sortable-header column="status" label="Status" />
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($events as $event)
        <tr>
          <td>{{ $loop->iteration + ($events->currentPage() - 1) * $events->perPage() }}</td>
          <td><strong>{{ $event->name }}</strong></td>
          <td>{{ $event->category ? $event->category->name : 'N/A' }}</td>
          <td>{{ $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('M d, Y g:i A') : 'N/A' }}</td>
          <td>{{ $event->city }}</td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input event-status-switch" data-id="{{ $event->id }}" data-status="{{ $event->status }}" {{ $event->status == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('events.edit', $event->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
            <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center mt-4 px-3 mb-3">
        <div class="text-muted" style="font-size: 0.85rem;">
            Showing {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} out of {{ $events->total() }} records
        </div>
        <div>
            {{ $events->appends(request()->query())->links() }}
        </div>
    </div>
  </div>
