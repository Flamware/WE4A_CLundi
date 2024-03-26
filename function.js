function user_view(user){
    document.cookie = "user_view="+user;
    location.reload();
    console.log("user_view");
}