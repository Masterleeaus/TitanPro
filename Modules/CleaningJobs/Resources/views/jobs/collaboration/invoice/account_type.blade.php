
<option value="JobBoard" @if($type == 'project') selected @endif {{(isset($_GET['account_type']) && $_GET['account_type'] == 'JobBoard') ? 'selected' : '' }}>
    {{ __('Projects') }}
</option>
