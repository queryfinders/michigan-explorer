    <div class="table-responsive pt-0">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>SR NO</th>
            <x-sortable-header column="email" label="Email" />
            <x-sortable-header column="source" label="Source" />
            <x-sortable-header column="is_verified" label="Verified" />
            <x-sortable-header column="created_at" label="Subscription Date" />
            <x-sortable-header column="verified_at" label="Verified Date" />
            <th width="100" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($subscribers as $index => $sub)
          <tr>
            <td>{{ ($subscribers->currentPage() - 1) * $subscribers->perPage() + $loop->iteration }}</td>
            <td>
              <strong class="text-dark">{{ $sub->email }}</strong>
            </td>
            <td>
              @if($sub->source === 'explorer_club')
                <span class="badge bg-label-primary">Explorer Club</span>
              @else
                <span class="badge bg-label-secondary">Footer</span>
              @endif
            </td>
            <td>
              @if($sub->is_verified)
                <span class="badge bg-label-success"><i class="fa-solid fa-circle-check me-1"></i> Verified</span>
              @else
                <span class="badge bg-label-warning"><i class="fa-solid fa-clock me-1"></i> Pending</span>
              @endif
            </td>
            <td class="small text-muted">{{ $sub->created_at->format('M d, Y H:i A') }}</td>
            <td class="small text-muted">
              {{ $sub->verified_at ? $sub->verified_at->format('M d, Y H:i A') : 'N/A' }}
            </td>
            <td class="text-center">
              <button type="button" class="btn btn-sm btn-danger delete-subscriber-btn" data-id="{{ $sub->id }}"><i class="fa fa-trash"></i></button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-center py-4 text-muted">No subscribers found matching the filters.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
      
      <div class="d-flex justify-content-between align-items-center mt-4 px-3 mb-3 flex-wrap gap-2">
        <div class="text-muted" style="font-size: 0.85rem;">
          Showing {{ $subscribers->firstItem() ?? 0 }} to {{ $subscribers->lastItem() ?? 0 }} out of {{ $subscribers->total() }} records
        </div>
        <div>
          {{ $subscribers->appends(request()->query())->links() }}
        </div>
      </div>
    </div>
