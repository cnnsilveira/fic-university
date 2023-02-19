<?php
require_once plugin_dir_path(__FILE__) . 'GetPets.php';
require_once plugin_dir_path(__FILE__) . 'generatePet.php';
$getPets = new GetPets();

get_header(); ?>

<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);"></div>
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title">Pet Adoption</h1>
    <div class="page-banner__intro">
      <p>Providing forever homes one search at a time.</p>
    </div>
  </div>  
</div>

<div class="container container--narrow page-section">

  <div class="form-container">
    <form method="get" class="filter-form">
      <div class="form-col-wrap">
        <select name="petname">
          <option value="">Pet name</option>
          <?php
          foreach ($getPets->pets as $pet) { ?>
            <option <?php if ($_GET['petname'] == $pet->petname) { ?>selected="true" <?php }; ?>><?php echo $pet->petname?></option>
          <?php } ?>
        </select>
        <div class="small-inputs-wrap">
          <select name="minweight" value="<?php echo $_GET['minweight']?>">
            <option value="">Min. Weight</option>
            <?php for ($i = 1; $i <= 100; $i++) { ?>
                <option <?php if ($_GET['minweight'] == $i) { ?>selected="true" <?php }; ?>><?php echo $i ?></option>
            <?php }?>
          </select>
          <select name="maxweight">
            <option value="">Max. Weight</option>
            <?php for ($i = 1; $i <= 100; $i++) { ?>
                <option <?php if ($_GET['maxweight'] == $i) { ?>selected="true" <?php }; ?>><?php echo $i ?></option>
            <?php }?>
          </select>
        </div>
        <div class="small-inputs-wrap">
          <select name="minyear">
            <option value="">Min. Year</option>
            <?php for ($i = 2006; $i <= 2021; $i++) { ?>
                <option <?php if ($_GET['minyear'] == $i) { ?>selected="true" <?php }; ?>><?php echo $i ?></option>
            <?php }?>
          </select>
          <select name="maxyear">
            <option value="">Max. Year</option>
            <?php for ($i = 2006; $i <= 2021; $i++) { ?>
                <option <?php if ($_GET['maxyear'] == $i) { ?>selected="true" <?php }; ?>><?php echo $i ?></option>
            <?php }?>
          </select>
        </div>
      </div>
      <div class="form-col-wrap">
        <select name="species">
            <option value="">Species</option>
            <?php
            foreach ($species as $item) { ?>
              <option <?php if ($_GET['species'] == $item) { ?>selected="true" <?php }; ?>><?php echo $item; ?></option>
            <?php } ?>
          </select>
        <select name="favcolor">
          <option value="">Favorite Color</option>
          <?php
          foreach ($colors as $item) { ?>
            <option <?php if ($_GET['favcolor'] == $item) { ?>selected="true" <?php }; ?>><?php echo $item; ?></option>
          <?php } ?>
        </select>
        <select name="favfood">
          <option value="">Favorite Food</option>
          <?php
          foreach ($foods as $item) { ?>
            <option <?php if ($_GET['favfood'] == $item) { ?>selected="true" <?php }; ?>><?php echo $item; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-col-wrap">
        <select name="favhobby">
          <option value="">Hobby</option>
          <?php
          foreach ($hobbies as $item) { ?>
            <option <?php if ($_GET['favhobby'] == $item) { ?>selected="true" <?php }; ?>><?php echo $item; ?></option>
          <?php } ?>
        </select>
        <div class="small-inputs-wrap">
          <input type="reset" onClick="resetForm()">
          <input type="submit">
        </div>
      </div>
    </form>
  </div>

  <p>This page took <strong><?php echo timer_stop();?></strong> seconds to prepare. Found <strong><?php echo $getPets->count?></strong> results (showing the first <?php echo count($getPets->pets)?>).</p>

  <table class="pet-adoption-table" style="text-transform: capitalize;">
    <tr>
      <th>Name</th>
      <th>Species</th>
      <th>Weight</th>
      <th>Birth Year</th>
      <th>Hobby</th>
      <th>Favorite Color</th>
      <th>Favorite Food</th>
      <?php 
        if (current_user_can('administrator')) { ?>
          <th>Delete</th>
        <?php } ?>
    </tr>

    <?php
      foreach ($getPets->pets as $pet) { ?>
        <tr>
          <td><?php echo $pet->petname;   ?></td>
          <td><?php echo $pet->species;   ?></td>
          <td><?php echo $pet->petweight; ?></td>
          <td><?php echo $pet->birthyear; ?></td>
          <td><?php echo $pet->favhobby;  ?></td>
          <td><?php echo $pet->favcolor;  ?></td>
          <td><?php echo $pet->favfood;   ?></td>
          <?php 
            if (current_user_can('administrator')) { ?>
              <td style="text-align: center">
                <form action="<?php echo esc_url(admin_url('admin-post.php'));?>" method="POST">
                  <input type="hidden" name="action" value="deletepet">
                  <input type="hidden" name="idtodelete" value="<?php echo $pet->id; ?>">
                  <button class="delete-pet-button">X</button>
                </form>
              </td>
            <?php } ?>
        </tr>
      <?php } ?>
  
  </table>
  
  <?php 
    if (current_user_can('administrator')) { ?>
      <form action="<?php echo esc_url(admin_url('admin-post.php'))?>" class="create-pet-form" method="POST">
        <p>Enter just the pet name, the rest will be filled automatically</p>
        <input type="hidden" name="action" value="createpet">
        <input type="text" name="incomingpetname" placeholder="Name...">
        <button>Add Pet</button>
      </form>
    <?php } ?>

</div>

<script>
  function resetForm() {
    document.querySelector('.filter-form').reset();
  }
</script>

<?php get_footer(); ?>