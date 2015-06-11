<?php
    $calendar = $context->calendar;
    $event = $context->event;
    $datetime = $context->event_datetime;

    if ($datetime->starttime == NULL) {
        $start_time = '';
        $start_date =  '';
        $start_hour = '';
        $start_minute = -1;
        $start_am_pm = '';
    } else {
        $start_time = strtotime($datetime->starttime);
        $start_date = date('m/d/Y', $start_time);
        $start_hour = date('h', $start_time);
        $start_minute = date('i', $start_time);
        $start_am_pm = date('a', $start_time);
    }

    if ($datetime->endtime == NULL) {
        $end_time = '';
        $end_date =  '';
        $end_hour = '';
        $end_minute = -1;
        $end_am_pm = '';
    } else {
        $end_time = strtotime($datetime->endtime);
        $end_date = date('m/d/Y', $end_time);
        $end_hour = date('h', $end_time);
        $end_minute = date('i', $end_time);
        $end_am_pm = date('a', $end_time);
    }

    $recurs_until_date = date('m/d/Y', strtotime($datetime->recurs_until));
?>
<?php echo $calendar->name ?> &gt; <?php echo $event->title ?> &gt; 
<?php 
if ($context->recurrence_id != NULL) {
    echo 'Edit a Single Instance from Recurring Event';
} else {
    echo $datetime->id == NULL ? 'Add a Location, Date, and Time' : 'Edit Location, Date, and Time'; 
}

?>
<form action="" method="POST">
    <fieldset>
        <label for="location">Location*</label>
        <select id="location" name="location" class="use-select2">
            <optgroup label="Your saved locations">
                <?php foreach ($context->getUserLocations() as $location): ?>
                    <option <?php if ($datetime->location_id == $location->id) echo 'selected="selected"'; ?> 
                    value="<?php echo $location->id ?>"><?php echo $location->name ?></option>
                <?php endforeach ?>
                <option value="new">-- New Location --</option>
            </optgroup>
            <optgroup label="UNL Campus locations">
                <?php foreach ($context->getStandardLocations(\UNL\UCBCN\Location::DISPLAY_ORDER_MAIN) as $location): ?>
                    <option <?php if ($datetime->location_id == $location->id) echo 'selected="selected"'; ?> 
                    value="<?php echo $location->id ?>"><?php echo $location->name ?></option>
                <?php endforeach ?>
            </optgroup>
            <optgroup label="Extension locations">
                <?php foreach ($context->getStandardLocations(\UNL\UCBCN\Location::DISPLAY_ORDER_EXTENSION) as $location): ?>
                    <option <?php if ($datetime->location_id == $location->id) echo 'selected="selected"'; ?> 
                    value="<?php echo $location->id ?>"><?php echo $location->name ?></option>
                <?php endforeach ?>
            </optgroup>
        </select>

        <div id="new-location-fields" style="display: none;">
            <h6>New Location</h6>
            <label for="location-name">Name</label>
            <input type="text" id="location-name" name="new_location[name]">

            <label for="location-address-1">Address</label>
            <input type="text" id="location-address-1" name="new_location[streetaddress1]">

            <label for="location-address-2">Address 2</label>
            <input type="text" id="location-address-2" name="new_location[streetaddress2]">

            <label for="location-room">Room</label>
            <input type="text" id="location-room" name="new_location[room]">

            <label for="location-city">City</label>
            <input type="text" id="location-city" name="new_location[city]">

            <label for="location-state">State</label>
            <input type="text" id="location-state" name="new_location[state]">

            <label for="location-zip">Zip</label>
            <input type="text" id="location-zip" name="new_location[zip]">

            <label for="location-map-url">Map URL</label>
            <input type="text" id="location-map-url" name="new_location[mapurl]">

            <label for="location-webpage">Webpage</label>
            <input type="text" id="location-webpage" name="new_location[webpageurl]">

            <label for="location-hours">Hours</label>
            <input type="text" id="location-hours" name="new_location[hours]">

            <label for="location-directions">Directions</label>
            <textarea id="location-directions" name="new_location[directions]"></textarea>

            <label for="location-additional-public-info">Additional Public Info</label>
            <input type="text" id="location-additional-public-info" name="new_location[additionalpublicinfo]">

            <label for="location-type">Type</label>
            <input type="text" id="location-type" name="new_location[type]">

            <label for="location-phone">Phone</label>
            <input type="text" id="location-phone" name="new_location[phone]">

            <input type="checkbox" id="location-save" name="location_save"> 
            <label for="location-save">Save this location for future events</label>
        </div>

        <label for="room">Room</label>
        <input type="text" id="room" name="room" />


        <label for="start-date" >Start Date &amp; Time</label>
        <div class="date-time-select"><span class="wdn-icon-calendar"></span>
            <input id="start-date" value="<?php echo $start_date; ?>" 
                name="start_date" type="text" class="datepicker" /> @
            <select id="start-time-hour" name="start_time_hour">
                <option value=""></option>
            <?php for ($i = 1; $i <= 12; $i++) { ?>
                <option <?php if ($i == $start_hour) echo 'selected="selected"'; ?> 
                    value="<?php echo $i ?>"><?php echo $i ?></option>
            <?php } ?>
            </select> : 

            <select id="start-time-minute" name="start_time_minute">
                <option value=""></option>
                <?php for ($i = 0; $i < 60; $i+=5): ?>
                    <option <?php if ($i == $start_minute) echo 'selected="selected"'; ?> 
                        value="<?php echo $i; ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                <?php endfor; ?>
            </select>

            <div id="start-time-am-pm" class="am_pm">
                <input <?php if ($start_am_pm == 'am') echo 'checked="checked"'; ?> 
                    type="radio" value="am" name="start_time_am_pm">AM<br>
                <input <?php if ($start_am_pm == 'pm') echo 'checked="checked"'; ?> 
                type="radio" value="pm" name="start_time_am_pm">PM
            </div>
        </div>

        <label for="end-date">End Date &amp; Time (Optional)</label>
        <div class="date-time-select"><span class="wdn-icon-calendar"></span>
            <input id="end-date" value="<?php echo $end_date; ?>"
                name="end_date" type="text" class="datepicker" /> @
            <select id="end-time-hour" name="end_time_hour">
                <option value=""></option>
            <?php for ($i = 1; $i <= 12; $i++) { ?>
                <option <?php if ($i == $end_hour) echo 'selected="selected"'; ?> 
                    value="<?php echo $i ?>"><?php echo $i ?></option>
            <?php } ?>
            </select> :

            <select id="end-time-minute" name="end_time_minute">
                <option value=""></option>
                <?php for ($i = 0; $i < 60; $i+=5): ?>
                    <option <?php if ($i == $end_minute) echo 'selected="selected"'; ?> 
                        value="<?php echo $i; ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                <?php endfor; ?>
            </select>

            <div id="end-time-am-pm" class="am_pm">
                <input <?php if ($end_am_pm == 'am') echo 'checked="checked"'; ?> 
                    type="radio" value="am" name="end_time_am_pm">AM<br>
                <input <?php if ($end_am_pm == 'pm') echo 'checked="checked"'; ?> 
                    type="radio" value="pm" name="end_time_am_pm">PM
            </div>
        </div>

        <?php if ($context->recurrence_id == NULL) : ?>
            <div class="section-container">
                <input <?php if ($datetime->recurringtype != 'none' && $datetime->recurringtype != NULL) echo 'checked="checked"' ?> type="checkbox" name="recurring" id="recurring"> 
                <label for="recurring">This is a recurring event</label>
                <div class="recurring-container date-time-select">                        
                    <label for="recurring-type">This event recurs </label>
                    <select id="recurring-type" name="recurring_type">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <optgroup label="Monthly" id="monthly-group">
                        </optgroup>
                        <option value="annually">Yearly</option>
                    </select>
                    <label for="recurs-until-date">until </label><br>
                    <span class="wdn-icon-calendar"></span>
                    <input value="<?php if ($datetime->recurringtype != 'none' && $datetime->recurringtype != NULL) echo $recurs_until_date; ?>" id="recurs-until-date" name="recurs_until_date" type="text" class="datepicker" />
                </div>
            </div>
        <?php endif; ?>

        <label for="directions">Directions</label>
        <textarea id="directions" name="directions"><?php echo $datetime->directions; ?></textarea>

        <label for="additional-public-info">Additional Public Info</label>
        <textarea id="additional-public-info" name="additional_public_info"><?php echo $datetime->additionalpublicinfo; ?></textarea>
    </fieldset>

    <button class="wdn-button wdn-button-brand wdn-pull-left" type="submit">Submit</button>
</form>

<script type="text/javascript">
WDN.initializePlugin('jqueryui', [function() {  
    $ = require('jquery');

    $('.datepicker').datepicker();
    $("LINK[href='//unlcms.unl.edu/wdn/templates_4.0/scripts/plugins/ui/css/jquery-ui.min.css']").remove();

    $('#location').change(function(change) {
        if ($('#location').val() == 'new') {
            $('#new-location-fields').show();
        } else {
            $('#new-location-fields').hide();
        }
    });

    $('#start-date').change(function (change) {
        setRecurringOptions($(this), $('#monthly-group'));
    });
}]);
</script>

<?php if ($datetime->recurringtype != 'none' && $datetime->recurringtype != NULL): ?>
<script type="text/javascript">
WDN.initializePlugin('jqueryui', [function() {  
    $ = require('jquery');

    setRecurringOptions($('#start-date'), $('#monthly-group'));
    $('#recurring-type').val('<?php echo $datetime->recurringtype ?>');
}]);
</script>
<?php endif; ?>
