<?php
/**
 * Created by PhpStorm.
 * User: borsosalbert
 * Date: 2014.07.15.
 * Time: 18:28
 */

namespace albertborsos\yii2user\languages\hu;


class Messages {
    public static $login_successful = '<h4>Sikeres bejelentkezés!</h4>';
    public static $logout_succesful = '<h4>Sikeresen kijelentkeztél!</h4>';

    public static $registration_succesful = '<h4>Sikeres regisztráció!</h4>';

    public static $activation_successful = '<h4>Sikeres aktiválás!</h4><p>Most már be tudsz lépni az oldalra!</p>';
    public static $activation_error_wrong_link = '<h4>Hibás aktiváló link!</h4><p>Nem megfelelő linket használsz!</p>';
    public static $activation_error_wrong_key = '<h4>Nem megfelelő aktiválókulcs!</h4><p>Vagy már beaktiváltad a fiókod! Próbálj meg belépni!</p>';
    public static $activation_error_wrong_email = '<h4>Nincs ilyen emailcím a rendszerben!</h4><p>Előbb be kell regisztrálnod, hogy aktiválni tudd a fiókod!</p>';
    public static $activation_error = '<h4>Nem sikerült bekativálni a fiókod</h4>';

    public static $reminder_email_sent = '<h4>Jelszóemlékeztető kiküldve!</h4><p>A pontos tennivalókért olvasd el a levelet, amit küldtünk!</p>';
    public static $reminder_error = '<h4>Jelszóemlékeztetőt nem sikerült kiküldeni!</h4>';

    public static $new_password_successfully_changed = '<h4>Sikeresen frissítetted a jelszavad!</h4>';
    public static $new_password_error_wrong_link = '<h4>Nem megfelelő a link...</h4><p>... vagy már lejárt a jelszóemlékeztetőd. Próbálj meg kérni egy újat!</p>';
    public static $new_password_error_email = '<h4>Az email cím nem egyezik a rendszerben tárolttal!</h4>';
    public static $new_password_error_valid = '<h4>Az új jelszavak nem egyeznek!</h4>';

    public static $user_remove_successful = ' felhasználót sikeresen törölted a rendszerből!';
    public static $user_remove_error = ' felhasználó jogosultságát nem sikerült eltávolítani!';
    public static $user_right_not_exists = 'Ilyen jogosultság nem létezik!';

    public static $user_details_update_successful = '<h4>Adataidat sikeresen módosítottad!</h4>';
    public static $user_details_update_error = '<h4>Adataidat nem sikerült módosítani!</h4>';

}