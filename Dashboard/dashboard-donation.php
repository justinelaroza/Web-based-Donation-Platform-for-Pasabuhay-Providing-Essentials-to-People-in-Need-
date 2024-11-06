<?php 
    include_once "../Database/db.php";
    session_start();

    class Queries {

        private $db;

        public function __construct(DataBase $conn){
            $this->db = $conn->getConnection();
        }

        public function defaultSort() {
            $query = "SELECT * FROM goods_donation ORDER BY goods_id";
            $result = $this->db->query($query);
            return $result;
        }

        public function showDonation() {
            $result = $this->defaultSort();

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <form action='dashboard-donation-form.php' method='post' >
                        <td>" . $row['goods_id'] . "</td>
                        <td>" . $row['status'] . "</td>
                        <td>" . $row['province'] . "</td>
                        <td>" . $row['church'] . "</td>
                        <td>" . $row['first_name'] . "</td>
                        <td>" . $row['middle_name'] . "</td>
                        <td>" . $row['last_name'] . "</td>
                        <td>" . $row['type_of_goods'] . "</td>
                        <td>" . $row['donation_date'] . "</td>
                    </form>
                </tr>";
                }
            }

        }

    }

    $db = new DataBase();
    $queries = new Queries($db);

?>