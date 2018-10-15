<?php include ROOT . '/views/layouts/header.php'; ?>

<section>
    <div class="container">
        <div class="row">

            <h3>Кабинет пользователя</h3>
            <h1>ENIXAN ENTERTAINMENT</h1>

            <h4>Привет, <?php echo $user['login'];?>!</h4>
            <h4>Твой личный счет -> Побед:<?php echo $games['win'];?>| Поражений <?php echo $games['lose'];?>| Ничьи <?php echo $games['draw'];?></h4>
            <ul>
                <li><a href="/cabinet/edit">Редактировать данные</a></li>
                <!--<li><a href="/cabinet/history">Список покупок</a></li>-->
            </ul>
            
        </div>
    </div>

    <div class="container">
        <div class="row">


            <h4>Усли игроков онлайн меньше чем 2 игра открыта не будет</h4>
            <h3>Пользователи Online</h3>
            <table class="table-bordered table-striped table">
                <tr>
                    <th>ID игрока</th>
                    <th>Побед</th>
                    <th>Поражений</th>
                    <th>Ничьи</th>

                </tr>
                <?php foreach ( $usersonline as $users): ?>
                    <tr>
                        <td><?php echo $users['id']; ?></td>
                        <td><?php echo $users['win']; ?></td>
                        <td><?php echo $users['lose']; ?></td>
                        <td><?php echo $users['draw']; ?></td>

                    </tr>
                <?php endforeach; ?>
            </table>


            <div align="center">
                    <h2>Каждая вставка значения должна работать с базой - которая в свою очередь подтягивает значeние вставки второго пользователя но мои полномочия time to do все= *( </h2>
                <h4> Играет только X (ИКС БОЛЬШОЙ) либо O (большая англ буква O )</h4>
                <?php if($notOnegamer['count(id)']>1) :?>

                <form method="POST"  action="http://mvcgame/cabinet">




                    <?php

                    $errors = false; $x_wins = false; $o_wins = false; $count = 0;
                    for($id=1;$id<=9;$id++) {

                        if($id==4 or $id ==7) print "<br>";
                       echo "<input name= $id type=text size = 8";
                        if(isset($_POST['submit']) and  !empty($_POST[$id]))

                        {
                            if ($_POST[$id] == "X" or $_POST[$id] == "O")
                            {
                                $count +=1;
                                print " value = '$_POST[$id]' readonly>";

                            for ($a = 1, $b = 2, $c = 3; $a <= 7, $b <= 8, $c <= 9; $a += 3, $b += 3, $c += 3)
                            {
                                if ($_POST["$a"] == $_POST["$b"] and $_POST["$b"] == $_POST["$c"])
                                {
                                    if ($_POST["$a"] == "X")
                                    {
                                        $x_wins = true;
                                    } elseif ($_POST["$a"] == "O")
                                    {
                                        $o_wins = true;
                                    }
                                }
                            }

                            for ($a = 1, $b = 4, $c = 7; $a <= 3, $b <= 6, $c <= 9; $a += 1, $b += 1, $c += 1) {
                                if ($_POST["$a"] == $_POST["$b"] and $_POST["$b"] == $_POST["$c"]) {
                                    if ($_POST["$a"] == "X")
                                    {
                                        $x_wins = true;
                                    } elseif ($_POST["$a"] == "O")
                                    {
                                        $o_wins = true;
                                    }
                                }
                            }


                            for ($a = 1, $b = 5, $c = 9; $a <= 3, $b <= 5, $c >= 7; $a += 2, $b += 0, $c -= 2)
                            {
                                if ($_POST["$a"] == $_POST["$b"] and $_POST["$b"] == $_POST["$c"])
                                {
                                    if ($_POST["$a"] == "X")
                                    {
                                        $x_wins = true;

                                    } elseif ($_POST["$a"] == "O")
                                    {
                                        $o_wins = true;
                                    }
                                }
                            }


                        }




                            else {
                                print ">";
                            $errors = true;
                            }
                        }

                        else
                            {
                           echo ">";
                        }

                    }
                    ?>

                    <p><input name="submit" type="submit"> </p>
<!--                    <button type="reset" name="" id="" value="RESET">RESET</button>-->
                </form>
                <?php  if($o_wins)
                        {
                            echo "PLAYER O WINS";
                        }
                        elseif ($errors)
                        {
                          echo   "PLZ ENTER X OR O";
                        }

                        elseif ($x_wins)
                        {
                            echo "Player X WINS";
                        }

                        elseif ($count == 9 and !$o_wins and !$x_wins)
                        {
                            echo "DRAW";
                        }
                        else
                        {
                            print "Pleads ENTER X or O values";
                        }
                        ?>




            <?php endif;?>
            </div>





        </div>

    </div>

</section>


<?php include ROOT . '/views/layouts/footer.php'; ?>