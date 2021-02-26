<div class="row">
    <div class="col-md-3 col-xs-12 col-sm-12">
        <div class="tile-stats tile-red">
            <div class="icon"><i class="entypo-users"></i></div>
            <div class="num" data-start="0" data-end="<?= $total_active_user ?>" data-postfix="" data-duration="500" data-delay="0">0</div>
            <h3>Total Users</h3>
        </div>
    </div>

    <div class="col-md-3 col-xs-12 col-sm-12">
        <div class="tile-stats tile-green">
            <div class="icon"><i class="entypo-users"></i></div>
            <div class="num" data-start="0" data-end="<?= $total_active_user ?>" data-postfix="" data-duration="500" data-delay="600">0</div>
            <h3>Clients</h3>
        </div>
    </div>
    
    <div class="col-md-3 col-xs-12 col-sm-12">
        <div class="tile-stats tile-orange">
            <div class="icon"><i class="entypo-users"></i></div>
            <div class="num" data-start="0" data-end="<?= $total_active_user ?>" data-postfix="" data-duration="500" data-delay="600">0</div>
            <h3>Realtor</h3>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-9">
        <div class="calendar-env">
            <!-- Calendar Body -->
            <div class="calendar-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets_admin/js/fullcalendar/fullcalendar.min.js') ?>"></script>
<script src="<?= base_url('assets_admin/js/neon-calendar.js') ?>"></script>