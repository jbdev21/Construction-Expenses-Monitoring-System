<template>
    <div class="item p-3">
        <div class="row gx-2 justify-content-between align-items-center">
            <div class="col-auto">
                <img class="profile-image" src="/images/profiles/profile-1.png" alt="">
            </div><!--//col-->
            <div class="col">
                <div class="info"> 
                    <div class="desc">Amy shared a file with you. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </div>
                    <div class="meta">
                        <timeago :datetime="notification.created_at" :auto-update="60"></timeago>
                    </div>
                </div>
            </div><!--//col--> 
        </div><!--//row-->
        <a class="link-mask" href="notifications.html"></a>
    </div>
</template>

<script>
export default {
    props:[
        'notification'
    ]
}
</script>

<style>

</style>