<!-- NAVBAR -->
<nav>
    <i class='bx bx-menu'></i>

    <div
        style="text-align: center; font-family: 'Ethiopian Jiret', 'Abyssinica SIL', sans-serif; font-size: 12px; 
        position: absolute; bottom: 0; width: 100%; padding: 10px; color: black; z-index: -10">
        <?php

        require 'vendor/autoload.php'; // Make sure to include the Composer autoload file

        use Andegna\DateTimeFactory;
        use Andegna\DateTime as EthiopianDateTime;

        // Get the current Gregorian date
        $gregorianDate = new DateTime();

        // Convert the Gregorian date to Ethiopian date
        $ethiopianDate = new EthiopianDateTime($gregorianDate);

        // Format the Ethiopian date to display the current Ethiopian year
        $ethiopianYear = $ethiopianDate->format('Y ዓ.ም');

        // Display the copyright notice with HTML styling
        echo "<div style='text-align: center; font-family: 'Nyala', 'Abyssinica SIL', sans-serif;'>";
        echo "<p style='font-size: 12px;'>© $ethiopianYear</p>";
        echo "<p>ሰንበት አቴንዳንስ ሲስተም(SAS)</p>";
        echo "</div>";

        ?>
    </div>
    <div class="profile-dropdown">
        <a href="#" class="profile">
            <img src="<?php echo base_url('assets/images/avatar.png') ?>" alt="መገለጫ" />
        </a>

        <ul class="dropdown-menu">


            <li>
                <a href="<?php echo base_url('authority/edit_profile'); ?>"><i class='bx bx-user'></i>መገለጫ አርትዕ</a>
            </li>

            <li>
                <a href="<?php echo base_url('login/logout'); ?>"
                    style="color: red; padding: 10px 15px; display: flex; align-items: center; text-decoration: none; transition: background 0.3s ease;">
                    <i class='bx bx-log-out' style="margin-right: 10px;"></i>ውጣ
                </a>
            </li>

        </ul>
    </div>
</nav>
<!-- NAVBAR -->