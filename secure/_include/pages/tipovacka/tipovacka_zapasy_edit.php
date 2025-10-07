<?php
global $pdo;

$edit = $_GET['edit'] ?? "";
$tipovacka_id = $_POST['tipovacka_id'] ?? $_SESSION["user_tipdef"]; $poradi = $_POST['poradi'] ?? ""; $skupina = $_POST['skupina'] ?? "";
$team1_id = $_POST['team1_id'] ?? ""; $team2_id = $_POST['team2_id'] ?? ""; $team1_goals = $_POST['team1_goals'] ?? ""; $team2_goals = $_POST['team2_goals'] ?? "";
$datetime = $_POST['datetime'] ?? ""; $datetime_end = $_POST['datetime_end'] ?? ""; $koeficient = $_POST['koeficient'] ?? 1; $tip = $_POST['tip'] ?? 99;
$valid = $_POST['valid'] ?? 0;  $add = $_POST['add'] ?? 0;
?>

<div class="card-body">
    <?php
    if($add == 2):
        tipovacka_zapasy_edit($pdo, $edit, $tipovacka_id, $poradi, $skupina, $team1_id, $team2_id, $team1_goals, $team2_goals, $datetime, $datetime_end, $koeficient, $tip, $valid);
    endif;
        $sql = "SELECT * FROM zdef_tipovacka_zapasy WHERE id = :id";
        $res = $pdo->prepare($sql);
        $res->execute(['id'=>$edit]);
        $dev = $res->fetch();
        if ($add == 0):
            $tipovacka_id = $dev["tipovacka_id"]; $poradi = $dev["poradi"]; $skupina = $dev["skupina"]; $team1_id = $dev["team1_id"]; $team2_id = $dev["team2_id"]; $team1_goals = $dev["team1_goals"];
            $team2_goals = $dev["team2_goals"]; $datetime = $dev["datetime"]; $datetime_end = $dev["datetime_end"]; $koeficient = $dev["koeficient"]; $tip = $dev["tip"]; $valid = $dev["valid"];
        endif;
        ?>
    <form method="post">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="tipovacka_id">Tipovačka</label>
                <select name="tipovacka_id" id="tipovacka_id" class="custom-select">
                    <?php tipovacka_option_form($pdo, $tipovacka_id);?>
                </select>
            </div>
            <div class="form-group col-md-1">
                <label for="poradi">Pořadí</label>
                <input type="number" name="poradi" id="poradi" class="form-control text-left" value="<?php echo $poradi; ?>" />
            </div>
            <div class="form-group col-md-2">
                <label for="skupina">Skupina</label>
                <input type="text" name="skupina" id="skupina" class="form-control text-left" value="<?php echo $skupina; ?>" />
            </div>
            <div class="form-group col-md-3">
                <label for="datetime">Datum a čas zápasu</label>
                <input type="datetime-local" name="datetime" id="datetime" class="form-control text-left" value="<?php echo $datetime; ?>" />
            </div>
            <div class="form-group col-md-3">
                <label for="datetime_end">Datum a čas konce tipu</label>
                <input type="datetime-local" name="datetime_end" id="datetime_end" class="form-control text-left" value="<?php echo $datetime_end; ?>" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="team1_id">Tým (domácí)</label>
                <select name="team1_id" id="team1_id" class="custom-select">
                    <?php tipovacka_teams_option_form($pdo, $team1_id, $tipovacka_id);?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="team2_id">Tým (hosté)</label>
                <select name="team2_id" id="team2_id" class="custom-select">
                    <?php tipovacka_teams_option_form($pdo, $team2_id, $tipovacka_id);?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="team1_goals">Branky (domácí)</label>
                <input type="number" name="team1_goals" id="team1_goals" class="form-control text-left" value="<?php echo $team1_goals; ?>" />
            </div>
            <div class="form-group col-md-2">
                <label for="team2_goals">Branky (hosté)</label>
                <input type="number" name="team2_goals" id="team2_goals" class="form-control text-left" value="<?php echo $team2_goals; ?>" />
            </div>
            <div class="form-group col-md-3">
                <label for="tip">Tip</label>
                <select name="tip" id="visible" class="custom-select">
                    <option value="99)" <?php if ($tip==99): echo 'selected="selected"'; endif; ?>>99 - nevyhodnoceno</option>
                    <option value="1" <?php if ($tip==1): echo 'selected="selected"'; endif; ?>>1 - výhra domácí</option>
                    <option value="2" <?php if ($tip==2): echo 'selected="selected"'; endif; ?>>2 - výhra hosté</option>
                    <option value="0" <?php if ($tip==0): echo 'selected="selected"'; endif; ?>>0 - remíza</option>
                </select>
            </div>
            <div class="form-group col-md-1">
                <label for="koeficient">Koeficient</label>
                <input type="number" name="koeficient" id="koeficient" class="form-control text-left" value="<?php echo $koeficient; ?>" />
            </div>
        </div>
        <div class="form-row">
            <div class="custom-control custom-switch">
                <input type="checkbox" name="valid" id="valid" class="custom-control-input" value="1" <?php if ($valid==1): echo "checked"; endif;?> />
                <label for="valid" class="custom-control-label">valid</label>
            </div>
            <div class="form-group col-md-2">
                <input type="hidden" name="add" value="2" />
                <label for="submit">&nbsp;</label>
                <button type="submit" class="form-control btn btn-primary">Uložit zápas</button>
            </div>
            <div class="col-md-12 small">
                Založeno: <?php echo format_datetime_www($dev['ts_i']); ?>;
                Založil: <?php echo $dev['user_i']; ?>;
                Upraveno: <?php echo format_datetime_www($dev['ts_u']); ?>;
                Upravil: <?php echo $dev['user_u']; ?>
            </div>
        </div>
    </form>
</div>