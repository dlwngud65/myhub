<?php
//중요한 값을 입력 받고 데이터베이스를 통해 조회할 경우 3단계 보안설정을 해야한다.
//1.자바 스크립트 / 2. mysqli_real_escape_string // 3 . 쿼리문에서 나온 값과  2번과 같은지 확인 한다.
    session_start();
    include "../lib/dbconn.php";
?>
<meta charset="utf-8">
<?php 
//아이디 or 페스워드를 입력하지 않을 시.
if(empty($_POST['id'])){//값이 없으면 true
    echo("
    <script>
        window.alert('아이디를 입력바랍니다.');
        history.go(-1);
    </script>
    ");
    exit;
}else{
    
    $id = mysqli_real_escape_string($con,$_POST['id']);// dlwngud' or '1'='1  ~~~~적을 시 dlwngud\' or \'1\'=\'1
    //쿼리문에서 영향을 줄 수 있는 값,(예를 들면 단따옴표)앞에 \를 붙여주는 역할을 한다.
}
if(empty($_POST['pass'])){
    echo("
    <script>
        window.alert('비밀번호를 입력바랍니다.');
        history.go(-1);
    </script>
    ");
    exit;
}else{
    $pass =  mysqli_real_escape_string($con,$_POST['pass']);;
}

    //id :'dlwngud' or '1'='1'
    //id = dlwngud' or '1'='1  이렇게적으면 회원이 아니여도 참이다.
    // stripslashes ($id) 따옴표 처리한 문자열을 풉니다
    
    $sql ="select * from member where id='$id'";
    $result = mysqli_query($con, $sql) or die("실패원인:".mysqli_errno($con));
    
    $num_match = mysqli_num_rows($result);//쿼리문에 만족하는 레코드 갯수 리턴.
    
    
    
    
    
    if(!$num_match){
        echo("
    <script>
        window.alert('등록되지 않은 아이디입니다.');
        history.go(-1);
    </script>
    ");
       
    }else{
        $row = mysqli_fetch_array($result);//필드명으로 가져온다.
        $db_pass=$row[pass];
        
        if($pass !== $db_pass){
            echo("
         <script>
             window.alert('비밀번호가 틀립니다.');
             history.go(-1);
         </script>
        ");
         exit;
         
        }else{
            
            //아이디와 페스워드가 일치 할 시. 섹션값 저장.
            $userid = $row[id];
            //3차 방어
            if($id !== $userid){
                echo("
              <script>
                window.alert('등록되지 않은 아이디입니다.')
                history.go(-1)
              </script>
           ");
                exit;
            }
            $username = $row[name];
            $usernick = $row[nick];
            $userlevel = $row[level];
            
            $_SESSION['userid']=$userid;
            $_SESSION['username']=$username;
            $_SESSION['usernick']=$usernick;
            $_SESSION['userlevel']=$userlevel;
            
              echo("
         <script>
             location.href='../index.php';
         </script>
        "); 
        }
        
    }

?>