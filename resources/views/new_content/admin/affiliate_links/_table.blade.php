  <div class="table-responsive pt-0">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>SR NO</th>
          <x-sortable-header column="name" label="Name" />
          <x-sortable-header column="provider" label="Provider" />
          <x-sortable-header column="link" label="Destination Link" />
          <th>Total Clicks</th>
          <x-sortable-header column="is_active" label="Status" />
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($affiliateLinks as $link)
        <tr>
          <td>{{ $loop->iteration + ($affiliateLinks->currentPage() - 1) * $affiliateLinks->perPage() }}</td>
          <td><strong>{{ $link->name }}</strong></td>
          <td>{{ $link->provider ?? 'N/A' }}</td>
          <td><a href="{{ $link->link }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 250px;" title="{{ $link->link }}">{{ $link->link }}</a></td>
          <td><span class="badge bg-label-info">{{ number_format($link->total_clicks) }} Clicks</span></td>
          <td>
            <label class="switch">
              <input type="checkbox" class="switch-input status-toggle-switch" data-id="{{ $link->id }}" data-status="{{ $link->is_active }}" {{ $link->is_active == 1 ? 'checked' : '' }}>
              <span class="switch-toggle-slider"></span>
            </label>
          </td>
          <td>
            <a href="{{ route('affiliate-links.show', $link->id) }}" class="btn btn-sm btn-info" title="View Stats"><i class="fa fa-chart-bar"></i></a>
            <a href="{{ route('affiliate-links.edit', $link->id) }}" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
            <form action="{{ route('affiliate-links.destroy', $link->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this affiliate link?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach

        @if($affiliateLinks->isEmpty())
        <tr>
          <td colspan="7" class="text-center">No affiliate links found.</td>
        </tr>
        @endif
      </tbody>
    </table>
    
    <div class="d-flex justify-content-between align-items-center mt-4 px-3 mb-3">
        <div class="text-muted" style="font-size: 0.85rem;">
            Showing {{ $affiliateLinks->firstItem() ?? 0 }} to {{ $affiliateLinks->lastItem() ?? 0 }} out of {{ $affiliateLinks->total() }} records
        </div>
        <div>
            {{ $affiliateLinks->appends(request()->input())->links() }}
        </div>
    </div>
  </div>
