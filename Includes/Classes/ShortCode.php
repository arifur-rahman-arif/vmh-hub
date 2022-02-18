<?php
namespace VmhHub\Includes\Classes;

class ShortCode {
    public function __construct() {
        add_shortcode('vmh_user_recipe', [$this, 'showUsersRecipe']);
    }

    public function showUsersRecipe() {

        echo '<div class="recopies_content_area vmh_inside_profile">';
        getUserRecipes();
        echo '</div>';
    }
}