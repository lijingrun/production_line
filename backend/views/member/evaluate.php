<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/6
 * Time: 22:01
 */
?>

<div>
    <h3>服务评价</h3>
    <div style="font-size: 18px;">
    <form method="post">
        <p>服务：
<!--            <select name="service">-->
<!--                <option value="3">好评</option>-->
<!--                <option value="2">中评</option>-->
<!--                <option value="1">差评</option>-->
<!--            </select>-->
            <input name="service" value="3" type="radio" checked />好评
            <input name="service" value="2" type="radio" />中评
            <input name="service" value="1" type="radio" />差评
        </p>
        <p>手艺：
<!--            <select name="craft">-->
<!--                <option value="3">好评</option>-->
<!--                <option value="2">中评</option>-->
<!--                <option value="1">差评</option>-->
<!--            </select>-->
            <input name="craft" value="3" type="radio" checked />好评
            <input name="craft" value="2" type="radio" />中评
            <input name="craft" value="1" type="radio" />差评
        </p>
        <p>时间：
<!--            <select name="use_time">-->
<!--                <option value="3">好评</option>-->
<!--                <option value="2">中评</option>-->
<!--                <option value="1">差评</option>-->
<!--            </select>-->
            <input name="use_time" value="3" type="radio" checked />好评
            <input name="use_time" value="2" type="radio" />中评
            <input name="use_time" value="1" type="radio" />差评
        </p>
        <p>沟通：
<!--            <select name="com">-->
<!--                <option value="3">好评</option>-->
<!--                <option value="2">中评</option>-->
<!--                <option value="1">差评</option>-->
<!--            </select>-->
            <input name="com" value="3" type="radio" checked />好评
            <input name="com" value="2" type="radio" />中评
            <input name="com" value="1" type="radio" />差评
        </p>
        <p>评价：
            <textarea name="content"></textarea>
        </p>
        <div>
            <input type="submit" value="评价" />
        </div>
    </form>
    </div>
</div>
