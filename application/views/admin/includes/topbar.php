<!-- NAVBAR -->
<nav>
    <i class='bx bx-menu'></i>

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