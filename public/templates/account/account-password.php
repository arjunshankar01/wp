<form class="dr-panel-edit needs-validation" novalidate>

    <div class="required-text">
        <?php echo __( 'Fields marked with * are mandatory' ); ?>
    </div>

    <div class="form-group dr-panel-edit__el">

        <div class="float-container float-container--pw-current">

            <label for="pw-current" class="float-label ">

                <?php echo __( 'Current Password *' ); ?>

            </label>

            <input id="pw-current" type="password" name="" class="form-control float-field float-field--pw-current" required>

            <div class="invalid-feedback">

                <?php echo __( 'This field is required.' ); ?>

            </div>

        </div>

    </div>

    <div class="form-group dr-panel-edit__el">

        <div class="float-container float-container--pw-new">

            <label for="pw-new" class="float-label ">

                <?php echo __( 'New Password *' ); ?>

            </label>

            <input id="pw-new" type="password" name="pw-new" class="form-control float-field float-field--pw-new" required>

            <div class="invalid-feedback">

                <?php echo __( 'This field is required.' ); ?>

            </div>

        </div>

    </div>

    <div class="form-group dr-panel-edit__el">

        <div class="float-container float-container--pw-confirm">

            <label for="pw-confirm" class="float-label ">

                <?php echo __( 'Confirm New Password *' ); ?>

            </label>

            <input id="pw-confirm" type="password"  name="pw-confirm" class="form-control float-field float-field--pw-confirm" required>

            <div class="invalid-feedback">

                <?php echo __( 'This field is required.' ); ?>

            </div>

        </div>

    </div>

    
    <div class="invalid-feedback dr-err-field" style="display: none"></div>

    <input type="submit" class="dr-btn dr-btn-green" value="<?php echo __( 'Save', 'digital-river-global-commerce' );?>">

</form>