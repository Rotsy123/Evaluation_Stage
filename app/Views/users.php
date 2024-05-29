<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>CodeIgniter 4 Pagination with Search Filter</title>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <style type="text/css">
        a {
            padding-left: 5px;
            padding-right: 5px;
            margin-left: 5px;
            margin-right: 5px;
        }

        .pagination li.active {
            background: deepskyblue;
            color: white;
        }

        .pagination li.active a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class='container' style='margin-top: 20px;'>

        <h3 style="text-align: center;margin-bottom: 20px;">CodeIgniter 4 Pagination with Search Filter</h3>
        <!-- Search form -->
        <form method='get' action="users" id="searchForm">
            <input type='text' name='search' value='<?= $search ?>' placeholder="Search here...">
            <input type='button' id='btnsearch' value='Submit' onclick='document.getElementById("searchForm").submit();'>
        </form>
        <br />

        <table class="table table-hover" style='border-collapse: collapse;'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>" . $user['id'] . "</td>";
                    echo "<td>" . $user['name'] . "</td>";
                    echo "<td>" . $user['email'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Paginate -->
        <div style='margin-top: 10px;'>
            <?= $pager->links() ?>
        </div>

    </div>
</body>

</html>