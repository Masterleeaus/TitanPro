<div class="table-responsive">
  <table class="table align-middle">
    <thead>
      <tr>
        @foreach($headers as $h)
          <th>{{ $h }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
        <tr>
          @foreach($r as $cell)
            <td>{!! $cell !!}</td>
          @endforeach
        </tr>
      @empty
        <tr><td colspan="{{ count($headers) }}"><x-cleaningjobs::empty>Connect data to see Cleaning Jobs here.</x-cleaningjobs::empty></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
