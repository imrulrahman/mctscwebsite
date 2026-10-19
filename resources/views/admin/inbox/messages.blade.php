@extends('admin.layout')
@section('title', 'Contact Messages · MCTSC Admin')
@section('crumb', 'Messages')

@section('content')
<div class="page-head">
  <div>
    <h1>Contact Messages</h1>
    <p>Inquiries and messages submitted through the website contact form</p>
  </div>
</div>

<section class="panel">
  <div class="panel-head">
    <h2>All Messages ({{ $items->total() }})</h2>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Sender</th>
          <th>Contact Info</th>
          <th>Subject</th>
          <th>Message</th>
          <th>Received At</th>
          @can('messages.delete')
            <th style="text-align:right;">Actions</th>
          @endcan
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
          <tr>
            <td>
              <strong style="color:#18362b;font-size:14px;">{{ $item->name }}</strong>
            </td>
            <td>
              <span style="color:#0f5132;font-weight:600;display:block;">{{ $item->email }}</span>
              <small class="subline">{{ $item->phone }}</small>
            </td>
            <td>
              <strong style="color:#334155;">{{ $item->subject ?: '—' }}</strong>
            </td>
            <td style="max-width:350px;color:#475569;line-height:1.4;">
              {{ $item->message }}
            </td>
            <td>
              {{ $item->created_at->format('d M, Y') }}
              <small class="subline">{{ $item->created_at->format('h:i A') }}</small>
            </td>
            @can('messages.delete')
              <td style="text-align:right;">
                <form method="post" action="{{ route('admin.messages.destroy', $item) }}" onsubmit="return confirm('Are you sure you want to delete this message?');" style="display:inline;">
                  @csrf
                  @method('delete')
                  <button type="submit" class="delete-link" title="Delete">Delete</button>
                </form>
              </td>
            @endcan
          </tr>
        @empty
          <tr>
            <td colspan="6" class="empty">No messages received yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($items->hasPages())
    <div class="pagination">
      {{ $items->links() }}
    </div>
  @endif
</section>
@endsection
