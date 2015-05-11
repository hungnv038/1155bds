<?php use App\Libraries\Constants; ?>
<script>
    $(function(){
        $('div.btn-group[data-toggle-name]').each(function () {
            var group = $(this);
            var form = $('#condition_form').eq(0);
            var name = group.attr('data-toggle-name');
            var hidden = $('input[name="' + name + '"]', form);
            $('button', group).each(function () {
                var button = $(this);
                button.on('click', function () {
                    hidden.val($(this).val());
                    $('button',group).removeClass('active');
                    if (button.val() == hidden.val()) {
                        button.addClass('active');
                    }
                });
                if (button.val() == hidden.val()) {
                    button.addClass('active');
                }
            });
        });
        $('div.minutes[data-name]').each(function () {
            var that = $(this);
            var name = $(this).attr('data-name');
            var hidden = $('input[name="' + name + '"]');
            $('a', that).each(function () {
                $(this).on('click', function () {
                    $('a', that).removeClass('label-danger');
                    hidden.val($(this).text());
                    $('.at_time').html('{{Lang::get('app.attime')}}' + '<i class="label label-danger">'+$(this).text()+'</i>');
                    $(this).addClass('label-danger');
                });
            });
        });
        $('select[name="operator"]').each(function () {
            if($(this).val() == '{{Constants::OPERATOR_NIN}}' || $(this).val() == '{{Constants::OPERATOR_IN}}') {
                $('.condition_value_last').show();
            } else {
                $('.condition_value_last').hide();
            }
            $(this).on('change', function () {
                if($(this).val() == '{{Constants::OPERATOR_NIN}}' || $(this).val() == '{{Constants::OPERATOR_IN}}') {
                    $('.condition_value_last').show();
                } else {
                    $('.condition_value_last').hide();
                }
            });
        });

        RuleModule.validationForm = $('#condition_form')
            .formValidation({
                framework: 'bootstrap',
                err: {
                    container: 'tooltip'
                },
                icon: {
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh'
                },
                row: {
                    selector: 'div.valid'
                },
                fields: {
                    "description": {
                        validators: {
                            notEmpty: {
                                message: 'The description is required'
                            }
                        }
                    },
                    "name": {
                        validators: {
                            notEmpty: {
                                message: 'The name is required'
                            },
                            stringLength: {
                                min: 1,
                                max: 225,
                                message: 'The name must be more than 1 and less than 225 characters long'
                            },
                            remote: {
                                type: 'GET',
                                url: RuleModule.urlApi + 'rules/validate',
                                data: {
                                    id: '{{isset($params['_id'])?$params['_id']:0}}'
                                },
                                message: 'The name condition existing in the system'
                            },
                            regexp: {
                                regexp: /^[a-zA-Z0-9_\.]+$/,
                                message: 'The name can only consist of alphabetical, number, dot and underscore'
                            },
                        }
                    },
                    "condition_values[value_first]": {
                        validators: {
                            between: {
                                min: -10,
                                max: 10,
                                message: 'The number of value must be between %s and %s'
                            },
                            notEmpty: {
                                message: 'The first value is required'
                            }
                        }
                    },
                    "condition_values[value_last]": {
                        validators: {
                            between: {
                                min: 'condition_values[value_first]',
                                max: 10,
                                message: 'The number of value must be between %s and %s'
                            }
                        }
                    }
                },
                onError: function(e) {
                },
                onSuccess: function(e) {
                }
            })
            .on('success.form.fv', function(e) {
                // Prevent form submission
                e.preventDefault();
                RuleModule.save();
            })
    });
</script>
<div class="box box-solid box-warning" id="rule_form">
    <div class="box-header">
        <h4 class="box-title panel-title"><i class="fa fa-cogs"></i> {{ isset($params['_id']) && $params['_id']?Lang::get('app.edit_condition'):Lang::get('app.create_condition_new')}}</h4>
        <div class="box-tools pull-right">
            <button class="btn btn-warning btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-warning btn-xs" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    {!! Form::open(array('method' => 'POST', 'role' => 'form', 'id' => 'condition_form')) !!}
    <div class="box-body">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="form-group row">
                    <div class="col-lg-12 valid">
                        {!! Form::label('name', Lang::get('app.name'), array('class' => 'control-label')) !!}
                        {!! Form::text('name',isset($params['name'])?$params['name']:'',array('class' => 'form-control', 'placeholder' => Lang::get('app.enter_name'))) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-12 valid">
                        {!! Form::label('description', Lang::get('app.description'), array('class' => 'control-label')) !!}
                        {!! Form::textarea('description',isset($params['description'])?$params['description']:'',array('rows'=>5,'class' => 'form-control', 'placeholder' => Lang::get('app.enter_description'))) !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8">
                <div class="form-group row">
                    <div class="btn-block btn-group" data-toggle-name="odd_type" data-toggle="buttons-radio">
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            {!! Form::label('odd_type', Lang::get('app.fulltime'), array('class' => 'control-label')) !!}
                            <div class="btn-block btn-group">
                                <button type="button" value="{{Constants::ODD_1X2}}" data-toggle="button" class="btn btn-primary">{{Lang::get('app.odd_1x2')}}</button>
                                <button type="button" value="{{Constants::ODD_AH}}" data-toggle="button"class="btn btn-primary">{{Lang::get('app.odd_ah')}}</button>
                                <button type="button" value="{{Constants::ODD_OU}}" data-toggle="button"class="btn btn-primary">{{Lang::get('app.odd_ou')}}</button>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-6 no-padding">
                            {!! Form::label('odd_type', Lang::get('app.firsthalf'), array('class' => 'control-label')) !!}
                            <div class="btn-block btn-group">
                                <button type="button" value="{{Constants::ODD_1X21ST}}" data-toggle="button" class="btn btn-primary">{{Lang::get('app.odd_1x2')}}</button>
                                <button type="button" value="{{Constants::ODD_AH1ST}}" data-toggle="button"class="btn btn-primary">{{Lang::get('app.odd_ah')}}</button>
                                <button type="button" value="{{Constants::ODD_OU1ST}}" data-toggle="button"class="btn btn-primary">{{Lang::get('app.odd_ou')}}</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::hidden('odd_type',isset($params['odd_type'])?$params['odd_type']:Constants::ODD_1X2,array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('time_type', Lang::get('app.time'), array('class' => 'control-label')) !!}
                    <div class="btn-group btn-block" data-toggle-name="time_type" data-toggle="buttons-radio">
                        <button type="button" value="{{Constants::TIME_PRE_MATCH}}" data-toggle="button" class="btn btn-primary">{{Lang::get('app.beforetime')}}</button>
                        <button type="button" value="{{Constants::TIME_HT}}" data-toggle="button" class="btn btn-primary">{{Lang::get('app.halftime')}}</button>
                        <div class="btn-group">
                            <button type="button" class="at_time btn disabled">{{Lang::get('app.attime')}}{!! ($params['time_type']==Constants::TIME_FULL?'<i class="label label-danger">'.$params['time_value'].'</i>':'')!!}</button>
                            <button type="button" value="{{Constants::TIME_FULL}}" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" style="min-width: 386px;">
                                <div class="pull-left minutes" data-name="time_value" style="padding: 5px 10px">
                                    @for($i=0;$i<=90;$i++)
                                        <?php $class = (isset($params['time_value']) && $params['time_value'] == $i) ? 'label-danger' : ''; ?>
                                        <a class="pull-left label {{$class}} label-info" style="width:24px;margin:2px;padding:5px 0;" href="">{{$i}}</a>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::hidden('time_type',$params['time_type'],array('class' => 'form-control')) !!}
                    {!! Form::hidden('time_value',$params['time_value'],array('class' => 'form-control')) !!}
                </div>
                <div class="form-group row">
                    <div class="col-lg-4 col-md-6 col-sm-5">
                        {!! Form::label('field', Lang::get('app.field'), array('class' => 'control-label')) !!}
                        <div class="btn-group btn-block" data-toggle-name="field" data-toggle="buttons-radio">
                            <button type="button" value="{{Constants::FIELD_HOME}}" data-toggle="button" class="btn btn-primary">{{Lang::get('app.home')}}</button>
                            <button type="button" value="{{Constants::FIELD_DRAW}}" data-toggle="button" class="btn btn-primary">{{Lang::get('app.draw')}}</button>
                            <button type="button" value="{{Constants::FIELD_AWAY}}" data-toggle="button"class="btn btn-primary">{{Lang::get('app.away')}}</button>
                        </div>
                        {!! Form::hidden('field',isset($params['field'])?$params['field']:Constants::FIELD_HOME,array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 no-padding">
                        {!! Form::label('operator', Lang::get('app.choose_operator_condition'), array('class' => 'control-label')) !!}
                        {!! Form::select('operator', $conditions, isset($params['operator'])?$params['operator']:'',array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-6 col-md-4 col-sm-5 no-padding">
                        <div class="form-group">
                            <div class="col-lg-6 col-md-6 col-sm-6 valid">
                                {!! Form::label('condition_value_first', Lang::get('app.enter_value'), array('class' => 'control-label')) !!}
                                {!! Form::text('condition_values[value_first]',$params['condition_values']['value_first'],array('class' => 'form-control', 'placeholder' => Lang::get('app.enter_value'))) !!}
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 valid no-padding condition_value_last" style="display: none">
                                {!! Form::label('condition_value_first', Lang::get('app.enter_value'), array('class' => 'control-label')) !!}
                                {!! Form::text('condition_values[value_last]',$params['condition_values']['value_last'],array('class' => 'form-control', 'placeholder' => Lang::get('app.enter_value'))) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::hidden('_id',isset($params['_id'])?$params['_id']:0,array('class' => 'form-control')) !!}
    </div>
    <div class="box-footer clearfix">
        <div class="pull-right">

            {!! (Form::submit('Save', array('class' => 'btn btn-sm btn-small btn-primary', 'data-loading-text' => 'Saving...'))) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>