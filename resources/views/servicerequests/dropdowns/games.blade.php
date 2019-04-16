<option value="">--Select Game--</option>
@foreach($games as $game)
    <option value="{{ $game->id }}">{{ $game->id." | ".$game->game_title }}</option>
    @endforeach