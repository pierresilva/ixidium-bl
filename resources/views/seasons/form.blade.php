<div class="form-group {{ $errors->has('code') ? 'has-error' : ''}}">
    <label for="code" class="col-md-4 control-label">{{ 'Code' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="code" type="text" id="code" value="{{ $season->code or ''}}" >
        {!! $errors->first('code', 'class') !!}
    </div>
</div><div class="form-group {{ $errors->has('season') ? 'has-error' : ''}}">
    <label for="season" class="col-md-4 control-label">{{ 'AdmiSeason' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="season" type="text" id="season" value="{{ $season->season or ''}}" required>
        {!! $errors->first('season', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('periods') ? 'has-error' : ''}}">
    <label for="periods" class="col-md-4 control-label">{{ 'Periods' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="periods" type="text" id="periods" value="{{ $season->periods or ''}}" required>
        {!! $errors->first('periods', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('start_date') ? 'has-error' : ''}}">
    <label for="start_date" class="col-md-4 control-label">{{ 'Start Date' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="start_date" type="datetime-local" id="start_date" value="{{ $season->start_date or ''}}" required>
        {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('end_date') ? 'has-error' : ''}}">
    <label for="end_date" class="col-md-4 control-label">{{ 'End Date' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="end_date" type="datetime-local" id="end_date" value="{{ $season->end_date or ''}}" required>
        {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('start_date') ? 'has-error' : ''}}">
    <label for="start_date" class="col-md-4 control-label">{{ 'Start Date' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="start_date" type="number" id="start_date" value="{{ $season->start_date or ''}}" required>
        {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="col-md-4 control-label">{{ 'Description' }}</label>
    <div class="col-md-6">
        <textarea class="form-control" rows="5" name="description" type="textarea" id="description" required>{{ $season->description or ''}}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="col-md-4 control-label">{{ 'Status' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="status" type="text" id="status" value="{{ $season->status or ''}}" required>
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Create' }}">
    </div>
</div>
