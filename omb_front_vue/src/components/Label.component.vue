<template>
    <h5>
        <span :class="cls" style = "white-space: normal">
            <slot></slot>
        </span>
    </h5>
</template>

<script>
export default {
    props: ['col', 'color'], 
    computed:  {
        cls() {
            return "badge badge-pill badge-" + (this.color || "secondary");
        }
    }
}
</script>
