<template>
  <div>
    <div class="mb-3">
        <label for="">Items *</label>
        <textarea class="form-control mb-2" style="height: auto !important" rows="6" required name="items"></textarea>
    </div>
    <div class="mb-3">
        <label for="">Amount *</label>
        <div class="input-group mb-3">
            <span class="input-group-text">&#8369;</span>
            <input type="number" required step=".01" min="1" class="form-control" name="amount">
        </div>
    </div>
  </div>
</template>

<script>
export default {

}
</script>

<style>

</style>