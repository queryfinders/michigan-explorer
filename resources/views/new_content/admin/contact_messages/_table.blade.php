    <div class="table-responsive pt-0">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>SR No</th>
            <x-sortable-header column="full_name" label="Name" />
            <x-sortable-header column="email" label="Email" />
            <x-sortable-header column="phone" label="Phone" />
            <x-sortable-header column="subject" label="Subject" />
            <!-- <th>Status</th> -->
            <x-sortable-header column="created_at" label="Submitted Date" />
            <th width="120" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($messages as $msg)
          <tr>
            <td>{{ ($messages->currentPage() - 1) * $messages->perPage() + $loop->iteration }}</td>
            <td><strong>{{ $msg->full_name }}</strong></td>
            <td><a href="mailto:{{ $msg->email }}">{{ $msg->email }}</a></td>
            <td>
              @if($msg->phone)
                <a href="tel:{{ $msg->phone }}">{{ $msg->phone }}</a>
              @else
                N/A
              @endif
            </td>
            <td>{{ Str::limit($msg->subject, 30) }}</td>
            <!-- <td>
              @if($msg->status === 'new')
                <span class="badge bg-label-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> New</span>
              @elseif($msg->status === 'read')
                <span class="badge bg-label-warning"><i class="fa-regular fa-envelope-open me-1"></i> Read</span>
              @elseif($msg->status === 'replied')
                <span class="badge bg-label-success"><i class="fa-solid fa-reply me-1"></i> Replied</span>
              @else
                <span class="badge bg-label-secondary"><i class="fa-solid fa-circle-check me-1"></i> Closed</span>
              @endif
            </td> -->
            <td class="small text-muted">{{ $msg->created_at->format('M d, Y h:i A') }}</td>
            <td class="text-center">
              <button type="button" class="btn btn-sm btn-danger delete-message-btn" data-id="{{ $msg->id }}" title="Delete"><i class="fa fa-trash"></i></button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center py-4 text-muted">No messages found matching the filters.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
      
      <div class="d-flex justify-content-between align-items-center mt-4 px-3 mb-3 flex-wrap gap-2">
        <div class="text-muted" style="font-size: 0.85rem;">
          Showing {{ $messages->firstItem() ?? 0 }} to {{ $messages->lastItem() ?? 0 }} out of {{ $messages->total() }} records
        </div>
        <div>
          {{ $messages->appends(request()->query())->links() }}
        </div>
      </div>
    </div>
