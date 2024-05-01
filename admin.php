<!-- COUNT FOR HOW MANY USERS -->
<?php
include('database_connect.php');
$sql = "SELECT COUNT(*) AS total_users FROM users";
$result = $mysqli->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $total_users = $row['total_users'];
    $result->close();
} else {
    $total_users = 0;
}
?>


<!-- SUBMITTING DAILY WORD -->
<?php
include('database_connect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $mysqli->prepare("INSERT INTO daily_word (daily_word_date, daily_word_title, daily_word_text) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $date, $title, $dailyWordText);
    $date = $_POST['date'];
    $title = $_POST['title'];
    $dailyWordText = $_POST['dailyWordText'];
    $stmt->execute();

    $stmt->close();
    $mysqli->close();
    header("Location: admin.php#dailyWord");
    exit();
}
$mysqli->close();
?>


<!-- DAILY WORD IN DASHBOARD -->
<?php
include('database_connect.php');
$sql = "SELECT daily_word_title FROM daily_word ORDER BY daily_word_id DESC LIMIT 1";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $dailyWordTitle = $row['daily_word_title'];
} else {
    $dailyWordTitle = "None";
}
$mysqli->close();
?>


<?php
include 'Database_connect.php';
$sql = "SELECT song_title FROM songs ORDER BY songdate_uploaded DESC LIMIT 1";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Output data of the latest song
    $row = $result->fetch_assoc();
    $latestSongTitle = $row["song_title"];
} else {
    $latestSongTitle = "None";
}
?>




















<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <title>admin</title>
</head>

<body class="body">
    <section class="header">
        <div class="logo">
            <i class="ri-menu-line menu"></i>
            <h2><span>Devotion</span>Diaries</h2>
        </div>
        <div class="profile">
            <h3 id="profadmin">Admin</h3>
            <img src="Images/signin.svg" alt="">
        </div>
    </section>

    <section class="main">
        <div class="sidebar">
            <ul class="sidebar--items">
                <li>
                    <a href="#" class="sidebar-link active" data-target="dashboard">
                        <span class="icon"><i class="ri-dashboard-line"></i></span>
                        <div class="sidebar--item">Dashboard</div>
                    </a>
                </li>


                <li>
                    <a href="#" class="sidebar-link" data-target="dailyWord">
                        <span class="icon"><i class="ri-book-line"></i></span>
                        <div class="sidebar--item">Daily Word</div>
                    </a>
                </li>


                <li>
                    <a href="#" class="sidebar-link" data-target="testimonials">
                        <span class="icon"><i class="ri-chat-4-line"></i></span>
                        <div class="sidebar--item">Testimonials</div>
                    </a>
                </li>
                <li>
                    <a href="#" class="sidebar-link" data-target="christianSongs">
                        <span class="icon"><i class="ri-music-line"></i></span>
                        <div class="sidebar--item">Christian Songs</div>
                    </a>
                </li>
                <li>
                    <a href="#" class="sidebar-link" data-target="trash">
                        <span class="icon"><i class="ri-delete-bin-line"></i></span>
                        <div class="sidebar--item">Trashbin</div>
                    </a>
                </li>
            </ul>
            <ul class="sidebar--bottom--items">
                <li>
                    <a href="index.php">
                        <span class="icon"><i class="ri-logout-box-r-line"></i></span>
                        <div class="sidebar--item">Logout</div>
                    </a>
                </li>
            </ul>
        </div>



        <div id="dashboard" class="tab">
            <div class="main--container">
                <div class="section--title">
                    <h3 class="title">Admin Dashboard</h3>
                </div>

                <div class="cards">
                    <div class="card card-1">
                        <div class="card--title">
                            <span class="card--icon icon"><i class="ri-users-line"></i></span>
                            <span>Total number of users</span>
                        </div>

                        <h3 class="card--value"><?php echo $total_users; ?></i></h3>
                    </div>


                    <div class="card card-2">
                        <div class="card--title">
                            <span class="card--icon icon"><i class="ri-calendar-event-line"></i></span>
                            <span>Today's Word</span>
                        </div>
                        <h3 class="card--value"><?php echo $dailyWordTitle; ?></h3>
                        <button class="card--button" id="dailyWordButton">Daily Word</button>
                    </div>


                    <div class="card card-3">
                        <div class="card--title">
                            <span class="card--icon icon"><i class="ri-chat-4-line"></i></span>
                            <span>Recent Testimony</span>
                        </div>
                        <h3 class="card--value">None</i></h3>
                        <button class="card--button">Testimonials</button>
                    </div>


                    <div class="card card-4">
                        <div class="card--title">
                            <span class="card--icon icon"><i class="ri-music-line"></i></span>
                            <span>Recent Christian song uploads</span>
                        </div>
                        <h3 class="card--value"><?php echo $latestSongTitle; ?></i></h3>
                        <button class="card--button" id="songsbuttonkick">Songs</button>
                    </div>
                </div>


                <div class="table">
                    <div class="section--title01">
                        <h3 class="title">Users Information</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Username</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'database_connect.php';
                            $sql = "SELECT userId, username, email FROM users";

                            // Execute the query
                            $result = $mysqli->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["userId"] . "</td>";
                                    echo "<td>" . $row["username"] . "</td>";
                                    echo "<td>" . $row["email"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No users found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <div id="dailyWord" class="tab" style="display: none;">
            <div class="main--container">
                <div class="section--title03">
                    <h3 class="title">Upload Daily Word </h3>
                </div>
                <br>

                <form id="dailyWordForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="dailyWordText">Daily Word Text:</label>
                        <textarea id="dailyWordText" name="dailyWordText" required></textarea>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="submit-button">Submit</button>
                        <button type="button" class="update-button" ">Update</button>
                        <button type=" button" class="cancel-button">Cancel</button>
                    </div>
                </form>

                <br>

                <div class="table">
                    <div class="section--title01">
                        <h3 class="title">Daily Word Information</h3>
                    </div>
                    
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Title</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DAILY WORD TABLE -->
                                <?php
                                include('database_connect.php');
                                $sql = "SELECT * FROM daily_word ORDER BY daily_word_id ASC";
                                $result = $mysqli->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row["daily_word_id"] . "</td>";
                                        echo "<td>" . $row["daily_word_date"] . "</td>";
                                        echo "<td>" . $row["daily_word_title"] . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3'>No daily word information available</td></tr>";
                                }
                                $mysqli->close();
                                ?>
                            </tbody>
                        </table>
                    

                </div>
                <!-- Search form and buttons -->
                <form id="searchForm">
                    <div class="form-group">
                        <label for="searchId">Search ID:</label>
                        <input type="text" id="searchId" name="searchId" required>
                    </div>
                    <div class="button-group">
                        <button type="button" class="edit-button">Edit</button>
                        <button type="button" class="delete-button">Delete</button>
                    </div>
                </form>
            </div>
        </div>


        <div id="testimonials" class="tab" style="display: none;">
            <div class="main--container">
                <div class="section--title03">
                    <h3 class="title">Testimonials</h3>
                </div>
                <br>


                <div class="table01">
                    <div class="section--title01">
                        <h3 class="title">User's Testimonials</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Username</th>
                                <th>Action</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>1</td>
                                <td>2024-03-21</td>
                                <td>User123</td>
                                <td>
                                    <button class="view-btn">View More</button>
                                    <button class="delete-btn">Delete</button>
                                </td>
                                <td>Approved</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>


                <div class="table">
                    <div class="section--title01">
                        <h3 class="title">Uploaded Testimonials</h3>
                    </div>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Username</th>
                                    <th>Ratings</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>0</td>
                                    <td>00-00-0000</td>
                                    <td>None</td>
                                    <td>0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- CHRISTIAN SONGS HTML -->
        <div id="christianSongs" class="tab" style="display: none;">
            <div class="main--container">
                <div class="section--title03">
                    <h3 class="title">Christian Songs</h3>
                </div>
                <br>

                <div class="upload--form">
                    <form action="adminconnect.php" method="POST" enctype="multipart/form-data">
                        <div class="form--group">
                            <label for="songTitle">Song Title:</label>
                            <input type="text" id="songTitle" name="songTitle" required>
                        </div>
                        <div class="form--group">
                            <label for="songArtist">Song Artist:</label>
                            <input type="text" id="songArtist" name="songArtist" required>
                        </div>
                        <div class="form--group">
                            <label for="dateUploaded">Date Uploaded:</label>
                            <input type="date" id="dateUploaded" name="dateUploaded" required>
                        </div>
                        <div class="form--group">
                            <label for="songPicture">Song Picture:</label>
                            <input type="file" id="songPicture" name="songPicture" accept="image/*" required>
                        </div>
                        <div class="form--group">
                            <label for="songFile">Song File:</label>
                            <input type="file" id="songFile" name="songFile" accept="audio/mpeg" required>
                        </div>
                        <div class="form--group">
                            <button type="submit" id="songbtn">Upload Song</button>
                        </div>
                    </form>
                </div>

                <div class="table">
                    <div class="section--title01">
                        <h3 class="title">Uploaded Christian Songs</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Song Title</th>
                                <th>Date Uploaded</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'Database_connect.php';
                            $sql = "SELECT * FROM songs";
                            $result = $mysqli->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["songs_id"] . "</td>";
                                    echo "<td>" . $row["song_title"] . "</td>";
                                    echo "<td>" . $row["songdate_uploaded"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr>";
                                echo "<td>0</td>";
                                echo "<td>None</td>";
                                echo "<td>00-00-0000</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


                <form id="searchForm">
                    <div class="form-group">
                        <label for="searchId">Search ID:</label>
                        <input type="text" id="searchId" name="searchId" required>
                    </div>
                    <div class="button-group">
                        <button type="button" class="edit-button">Edit</button>
                        <button type="button" class="delete-button">Delete</button>
                        <button type="button" class="update-button">Update</button>
                    </div>
                </form>
            </div>
        </div>



        <!-- TRASH HTML -->
        <div id="trash" class="tab" style="display: none;">
            <div class="main--container">
                <div class="section--title04">
                    <h3 class="title">Trash</h3>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>0</td>
                                <td>None</td>
                                <td>
                                    <select name="type" id="type">
                                        <option value="Word">Word</option>
                                        <option value="Testimony">Testimony</option>
                                        <option value="Song">Song</option>
                                    </select>
                                </td>
                                <td>
                                    <button class="button1">Delete</button>
                                    <button class="button1">Restore</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="button-group">
                    <button type="button" id="deleteAll">Delete All</button>
                    <button type="button" id="restoreAll">Restore All</button>
                </div>

            </div>
        </div>




    </section>











    <script>
        // FOR THE SIDEBAR
        let body = document.querySelector(".body")


        let menu = document.querySelector(".menu")
        let sidebar = document.querySelector(".sidebar")
        let mainContainer = document.querySelector(".main--container")

        menu.onclick = function() {
            sidebar.classList.toggle("activemenu")
        }
        mainContainer.onclick = function() {
            sidebar.classList.remove("activemenu")
        }
    </script>

    <!-- tabs -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tabs = document.querySelectorAll('.tab');
            const sidebarLinks = document.querySelectorAll('.sidebar-link');

            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = this.getAttribute('data-target');

                    tabs.forEach(tab => {
                        if (tab.id === target) {
                            tab.style.display = 'block';
                        } else {
                            tab.style.display = 'none';
                        }
                    });

                    sidebarLinks.forEach(sidebarLink => {
                        sidebarLink.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });
        });
    </script>


    <!-- IT WILL GO TO DAILY WORD TAB -->
    <script>
        document.getElementById("dailyWordButton").addEventListener("click", function() {
            document.getElementById("dailyWord").style.display = "block";
        });
    </script>

    <!-- IT WILL GO TO songs TAB -->
    <script>
        document.getElementById("songsbuttonkick").addEventListener("click", function() {
            document.getElementById("christianSongs").style.display = "block";
        });
    </script>

    <!-- CANCEL THE FORMS -->
    <script>
        document.querySelector(".cancel-button").addEventListener("click", function() {
            document.getElementById("dailyWordForm").reset();
        });
    </script>

    <!-- GO TO CHRISTIAN SONG TAB -->



</body>

</html>