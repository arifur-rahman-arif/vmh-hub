<!DOCTYPE html>
<html lang="en">

<head>
    <!--Required meta tag-->
    <meta charset="UTF-8" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php bloginfo('name');?><?php wp_title('|')?></title>
    <?php wp_head()?>
</head>

<body <?php is_home() ? '' : body_class()?>>
    <!--Start Header Area-->
    <header class="header_main_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="header_content_area">
                        <div class="logo_area">
                            <a href="<?php echo site_url('/') ?>"><img
                                    src="<?php echo VMH_URL . 'Assets/images/logo.png' ?>" alt="" /></a>
                        </div>
                        <div class="menu_area">
                            <div class="menu">
                                <?php getHeaderMenu()?>
                                <!-- <?php echo getSearchBar() ?> -->
                            </div>

                            <!-- start mobile menu icon -->
                            <div class="mobile-menu-icon">
                                <div class="all-p-humber">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                            <!-- end mobile menu icon -->
                        </div>
                        <div class="shiping_cart_area">
                            <?php if (is_user_logged_in() && !userRole('administrator')) {?>
                            <a class="top_shiping_box" href="<?php echo site_url('/earnings') ?>">

                                <svg class="vmh_user_earning" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="25"
                                    viewBox="0 0 398 516">
                                    <image x="2" y="2" width="394" height="512"
                                        xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAYoAAAIACAYAAACcvSszAAAgAElEQVR4nO3dLZQcR7Yg4LBnwCyaGmbmMhvmFpuH1GI7aNpsF7nFlrnNllli+5BsuKhl9KBk9N6gbqHdRWozM7XZsu5hs0h7Uo6yU6X6yazKn/j5vnPitEbWSFVZWXnzxr0R+VGAMi1CCCfxnS3jaPyx9fsrJ/HPH+o+hHCz9v+9DSH83Prf1/Hnpj8LSRMoyNlpKyB82goIy0ze000MHKugchvH6vchCQIFOTiJowkADzMLBse4bgWR61YggUkJFKRmETOFkxgUTn1C71lNXb2KP28ED8YmUJCCJhj8rRUg6Oc+Zhyr4HHt+DEkgYI5NFnDWSs4HFNI5kPtwHGteA7kogkG5yGEFyGEt8ak400I4TIGZ4DknMSL1J3gkMS4EzSAVDTZw5ULcxZBQ10ImNR5nOqo/SKc23gdPzv1ImA0ZwJEEaPJMp5Usk4FmMjSFFOx41LAAI71xMW0ivHElBTQ10IWUd1opqQufFOALk60ulY9rkxHAbucCxJGPAfOfVOAdecuksbauPQtqYO9nujqrSPFmmbX2s8clPJ9XPsBoDM7krLuO0ekDgIFXdmBlLZmh9rnjkgdBAq6euVI0fLS41rroUZBV4vY7QIh1iY8Wa8SMgq6ujf9RHQtSNRFoKAPBW2CInZ9BAr6UKfgNtYnqIhAQR8uEHxf/RGokGI2fTV7/Zw6atX6k26n+sgo6Mv0U72eCxJ1EijoS0G7XorYlTL1xCHs+1Sf5gbhUe0HoVYyCg4hq6iPInbFBAoO8YOjVpVb+zrVTaDgEDKKusgmKqdGwaHuPHC/GvZ1qpyMgkPJKurwXJBAoOBQ6hR1MO2EqScOtgwhvHH4itbsFvyg9oOAjILD3ZqSKJ4FdrwjULByHkJ4Hfdy6somgeXyqFPgnaZr6UnsYHrbGmcdD8/Z2v/PKGc86XgOnOp+gzJtCxCr0af2IDiUOZYdPvuT1vly4loBZdgXINrjvOM7vnJRLW686PC5LzacR12zECBR5x0DxGp0zSouXFiLG12eN/J6y/u+MhUF+TmNF/1DLmZd7hBPXFiLGl1uEJ7tec93pqIgD8s4hXDMRazrNh2HBiIjvbFvyrFPA0PX6UtgBhc9p5l2jS5ZxaULfhFj343B8oDz6pkLAKTlZMfc8aGjS1ahTbaMse+ifui5dek6AWkYs6i874u+cJEtYuxqid1Xl+hyDilyw0wWE7Wo7uur1yab99jVEjtUxvhasIDpnQ5Yi9g39mUV2mTzHttW4x9SlxAsIBFPZrgw7coqtMnmO3a1xI6RKQoWMLLFjF1G+zYM1Cab57jY8nmOeTOiwA0jWYzQ1dR37Fq1e2zB05h+bOtqO53gtQgWMLBlAkHi7Z6sQptsfmPTxXoxYXa4LZsBejqZsGjdZezKKmq/8OY2NtWdjl3RP+T5BHSQWpB4u6f4OfVFxjh8bMoO5+he67pVDLDBlFMAfce2fXzOXbizGeuf4Zw3JV22NgfWpFC43jW2ZRVLF+Asxvrnl8L51vXJikCUwxTOtqwi5QBn/DLWN3tMoWPtjSko6C6XVc7b5pat0k5/tD+3lLrVPCUPOshthfOmL7ZV2mmPdkvs0Ft0HDsUtqGD3KZttn2xrdJOd7SfPpfi+eaBR4n5uPYDkJiLDB8hudiyaOrlDK+F/a5DCDfxTz1L9Hz7KoHXAElaJLheos9YX7g1xRYQxuF366mvot+3rT1UKfcC8KatIHIOfCWOu/i5pFaX2DRs7ZEQU0/pyD3dPt9wF2j6KS3fxVfzIoOC8cMEXgMkpZRpmvXVtTYJTGssM9rhd9c2MVClkrbnbm/w5lna6YzLDAM3iTD1lIaSds/8pvXre9NPyXiV4fMfcusALJZAkYaSvhCna4HvhxlfC7+4iTWw3BayWXiXCIFifiV+GdpZhYxifkt35xxDoJhfiV/g01a/vumn+bkz5ygCBWNpZxWvHGXI10c+u9ktWguhSvM4hPA8Tn1od6Qv16dE+CDSUGor4G0I4UGcfnptnpwemnPmTw5YGkw9peG60Pe1bG3F8P3Mr4W83Pi80iFQpKHkL8WqLVNBmz7UtRIiUKSh5Lvt1Tbkt+4S6cGNRUIEijTcxAtpqVZZheknunBTkRiBIh1PC35vi7iflbtEunBDkRhdT2l5U/gDWz6LO8zqfmKb+3ie3DtC6ZBRpOVx4e/vG3eL7PGdIAH7lbTl+KbhGRXGtvHGdiNp+l3tByBBf497JZU6BfVJvGP8JIHXQlq+CCH85DNJj6mnNH1RcNfHaeEdXhzm24IXnmZPMTtdTcH3qtBU/Lbwoj39vIw3RyRKoEjbaQwWUKomc36kgJ02NYq0NXfeP8cCMJR4fv+LIJE+gSJ9N55QRoGa4PBX9ao8mHrKx9Xas6ghZ48Ur/Oh6ykfJXdCUZfHgkReBIp83McvmPlccvZtfOohGTH1lB+dUOTqeQXb1BRJMTs/OqHI0U0sXpMhgSJPOqHIyWqtxD99anky9ZQ3nVCk7j4GCY0YGRMo8raIwUJmQaoeCBL50/WUN51QpOyxIFEGgSJ/NzZUI0FPtcGWQzG7DDqhSEkTIL72iZRDoCiHTihSoA22QIrZ5dEJxVxsGV4ogaI8OqGYgzbYggkUZSr56XikSRtswXQ9lUknFFPSBgsZOw8hvDWMEccTF4jy6Xoqm04oxqQNthJqFHXQCcXQbmJdggqoUdTB0/EY0qoNlkrIKOqhE4ohaIOtkEBRF0/H41jaYCtk6qku1x5FyRG0wUJFLrWMGj2HNlio0JWLpdFxXLpA1E2Nol72hKILbbCoUVTM0/HYRxss78go0AnFJtpg+ZUtPPB0PDb5F0GCFYGCYE8o1jRTkv/hoACbvNYJVP3QBgvs1HRCvXGxrHZog2UjxWzW2ROqTtpg2Up7LOtubPMxqecJvAZtsOykmM0mP4UQ/hFC+M+OzqiexsxtziaC+7gN/e2MrwHImD2hxhuXsdNs7teh0w04mk6o4ceqaPxk5tdx7usBDEEn1LDjRfxMmuN6N+Pr0AYLDOpk5otaKeN1q5vsfMb3pA0WGMWZC/1R4/Vay/FcWdprXw9gTBcu+AdfnNtBYq6gu/46AEahE6rfuIvdTW1zPDTqTocTMCWdUIdfnE9mei2CBDApnVCHBYkwU0amDRaYhU6o3eN0w4cyxwI7bbDArHRCbR7b7uCnXmCnDRZIgk6o98e2IDH1AjttsEBSdEL9Mi52fChTLrDTBgskqfZOqH3TPFMV/7XBAsmquRNqX5CYspYjSDC43zukDGT1XIPUno532+FZC8sNi+K6et7hQU9fHf7ye3kcH0IEg/IoVIZ21tohdQo3MRD82AoKXYLDNsu18Xn835vu1FdPhrvf8/e9meA4fB1C+HaCfwdgEGN1Qr2JQehiyzqFsZ3Gf/tFHF0ypykK/dpggSwNdYFcBYZDp4bmNnaQuPL1AHJ2aCfUi9hOWkKL5zIGujG6wrTBAtnr0wn1uqDgsM2yNXV1bJDYtCstQJb27Ql1WWlL5yIW/p8d0FZsrQRQnPV1BHdx3yPTJr9ZtgLHvmkqu8ECRboQIHpbdVk9az3saNc2IQAAAAAAAAAAAAAA7GabcWow1E6z9573AFCeZwNuwnd34GLBJ3HVtYWGABM4i/tDddnGYoxHkD454C22t+R4HYPXHM/TACjWcm3zvC539ss9mxFOmVVsy2ruKt4UEWAQZ609jvpuijfG8x8OzSrOO/ydb+I+TqanADo437H1dpfnTw9Zlxgiq1j2/LsvPWsC4EOLeKe+b7poXzYxRl1iiKzikGmwS7UMgO4B4m2H50OPVZcYIqs45ql2VzIMoFa7ppg2jX1312PWJY7NKp4M8O+ZkgKqcXrA4z/3ZRNj1yWOzSpOBw5Qit5AkZZbupi6jF0tpF26iubOKhYjBCmPRAWKsThy6uVyx4E4mbAucWxWMcbU2JV1GEDuzg6YZlof2+blFzPUJY7JKi4TeA0AyVgc2enT5QI45oW3z+iaVYw9RfZGOy2Qi7OBpoN2XYDnrEscekd/MtFreabYDaRqqCxi38V37rpE36DWNtXrfq12AaRmqCxiNd5sufCmUpfYNLp0IR3a9XXoULsAZrcYqVaw7aKbSl1iW3DbZ4iFd32Hld3AbE4G6Gjqc8FNrS7RJ8CtTLUX1fq4i/82wGQuRryobbqgTVUIPnbsyyqGXnjXdzzzFQHGNnTBen1s2qpjMVLmMtbYl1XM/V5em4oCxjLWVFN7bFoHMGZgGmPsyypSqLPcWXMBDG2K+sCmbGLMKa4xx66sIqX3pCsKONpYXU2bxvp0SC51iU1jV1aR2vt6YYEecKjlhGsW1jf+y60usWnsyipSe63qFkBvpxOvfl6/SOVWl9g0dmUVUy+86zLULYDOpl6vsD5PnmtdYtPYllXMsfDu2NcM8M7UHTnreyQN+SS4FMa2rGKuhXddx65ngACVWsw0HdLOJhYJbvY3xNh0h77M4HUrcgO/Oplpo731jf9SnLcf6n1ukkOx/rVgAcy5ZXf7TjvlOfuh3+tKLgX7N7Ysh3rNucle+y67tLrEvve7klPR/k6wgPrMfQe/2viv1LrEprGeVeQYIHVEQSXm3muovVVHqXWJTWNTVpHj+xAsoGCpPB1utair9LpEl4tsroHSHlFQoCm349g1VtlEDXWJTWM9q3iW8Xux1gIKMmdn0/pYxlFLXWLTaGcVqS+82zcECyjAWUIX5dVFJYXMZs7RzipyWHjX5XO11gIyldozppeZT7UMOdpZRQnZlYV5kKHUCsVPCphmGXK0s4oSdsp9K1hAXlJ41GZ73CVWJ0llrLKKkrq/BAtI3CLRu9Mn6hIbxyqrKK0DTLCARKWyRmJ9vEkww0lprFaol/a+BAtIzFy7v3YZpcy/jzVW60pKzLgEC0hE6nP/6hL7x2nB3WCCRQE+rv0AZO4s3pGm/EV0kdjvmxDCj6m/yAOdZHCOsodAka9zTyArxmnhn6NgATOwaK28cVXBNJ1pKJiIDqJyRw31HMEiQx/VfgAysoiZhGcBkLubEMKjEMK9TzIPAkUeFnFqwqMoKYVgkRGBIn2CBKUSLDIhUKRNkKB0gkUGflf7AUjYSWx/FSQo2Sdx/OBTTpdAkaZV3/my9gNBFU7iuS5YJEqgSI/FSdRIsEiYQJEWQYKancRz/+/OgrQIFOkQJCCEv4QQfo5FbhIhUKRBkIDfnAkWaREo5idIwIcEi4RYRzEvQQK2u49rLASLmQkU8xEkYD/BIgECxTwW8VnSggTs1wSLz6zeno8HF01vIZOAXnxnZiajmJa9m+BwzfTTA8dverqepiNIwHE+sXp7HgLFdP49LiYCDmerjxkIFNO4jH3hwPFOrLGYlhrF+C49vhRG8UUI4aVDOz5dT+O6ECRgNJdqftOQUYznPJ7IwHjuYyfUrWM8HoFiHGfx6XTA+DxOdWQCxfBszQHTu47BghHoehpW07b3vwQJmNxS2+x4BIrhLOJaCc+5hnlomx2JqafhvNaBAUnQNjsw7bHD0KYH6fB9HJhAcbwn1kpAUhax61CtcCCmno5jrQSky26zA1HMPtxJLF4DabLb7EAEisOs2mD/kOOLh4rohBqAqaf+PFcC8vMoLsrjAAJFfy9sGQ7ZsSfUEXQ99fNMkIAs6YQ6gkDR3XncNhzI04kuxcMoZnejwwnK8Oc45a5e0YNAsd8ibs+hwwnKcBpC+DGE8JPPsxtTT7stbBkORbLNRw8CxW7PnExQpEUMFm4COzD1tF1TuP7vqb444GifxGHl9h4CxWbNHOa/pfjCgEE1Mwb/CCH8b4d1OwvuPtSkom+kpFAVK7d3ECg+5AFEUJ9m5fZn8SdrFLPfpxMC6rTqcGQDNYrfnMeHEAF1+iQGjL/7/N8nUPziJBavLaqDuv3FYrwPqVH8tvJ6mcBrAeZ3H4vbnmERqVH8UpcQJIAVi/HW1D71dGFHWGADi/Faag4UJ3F/eoBt14jqH6MaKq5RqEsAXVRfrwgV1yjUJYAuqq9XhEqnntQlgD6qr1fUFijUJYBDVF2vqKlGoS4BHKOpVzwIIdzWdhRrqlE8EySAIyxqnZGoZerJPk7AEKrcD6qGqadlnHKyyhIYyhchhJe1HM0aAoXnSwBDq+r5FaXXKJ4IEsAIqqpXlFyjOI0LZQDGsKzledulTj1phQWm8qD09RWlTj1phQWmUvwWHyVOPZ2FEP5HAq8DqMMn8emYxbbMljb11ET1N1phgRk0u8xel3jgS5t6qn6XR2A2L0q9/pQUKC7itBPAHBaldlqWUqNoCtf/FucJAeby5xDCjyGEn0r6BEqpUVzFdRNwiPV55dW5dNvaKXRh8SYdFbfLbAmB4iK2w0LbdfzC/hh/rvrcbwbcdqEdPJZxfBp/nqiXVe06FreLkHugsOEfqyDwKv68SehObhVImgzl8/hr63vq8XUI4dsS3m3ugcKUU33u493aq/gztxWxy3jOPow/BY5yVfugo5Q0U05vjSrGmzi9WOJNwTKey6+dy0WOqwTOsWo1Kf2dk7DosQoONRWQl3HH4zc+/6LGRQLnVpVeOPmKHW/iEwlrdy5gFDPuTDFO78yJV+QQIDYTMMoYpqAmZMqpvHHneeZ7LeIxqv1cyX2YgprIMydbUeNKSt7LiaJ31sMU1AROnWhFDXdXh5Nd5DuqeXzqXMzTljHubIUxiDPTsNkOm5eOxB1UGcMq+mGduIHKctz5Hgxv6cQqYlz5coxioW6R5ShyO/I5XTmpfCnYSbDIc9h+aCDWTOQ/9I9PQ7DIb7yp4cQc28L8a/ZDTWJa1hnlN7JYQ5Tyo1Av9Bxn7T7uxz/Usx/YzzHPz1euc4dTwM5/aIGdjynbvEbyaytSfWb2C1E2a80DW17WfhBm9FOchvpLtUcgL3+Oz1fx3Ioe3A3lPRSv06C4nddIurCdWkaxiNmEAmiemrnxv5ojT8I/Qwj/J4Tw32o/EJlYxCeOXqf4clMrZitg5+2p9DkpN/EzIQ9fuUneb6m1L+vxOvUTrFLazPMaFqfucekkyXpYZZoudb+8ho7BLWwhnvdQwE6frXDyGb5PWziJ8x6yifS5Gctr+E6tkRbnPdz95MMNWT4jqXbZFNpj/12lP2uPdTpl4+cQwnntByETi/h53aTwcj+a+d+/iM/BJk9NgPiskM9uGbPb5gv6MP5esx7kx/g+rwsJiFemNbLRnG8Pal+XZKfL/EcWO1/ucd6jffSqgDvyc+dtVqOE79hRPN40/5Hz4sjTI9YXXGXewmhdRT6j6semWlyX/8h5gd2zAY7/XcbZxRDv35huVJtVWFyX/8j15B363MsxWNjGP69RZVbhJC1j5FgQHWN+/i7TaSg7y+Y1qtvaQzZRxsjNmM0TOU7DXTiHsxuz1QSn3j12qY+7CEluhbzHsxHT95MMp+Jy/Axr900t79/K0DJGbmtfppjuvMuwC0z3U35jlnNsyozi1EKfYvyc2Rs5m+DfWGR4xyeryE/xWYVsopyRW8CfsnCbU1Zh8V2eY/JzbKqMQjZRlty2spiyKymnO74k9hGit8nPsan2erK/TFnm3iOsj9MZdrj9LKNg+jaB10B/k55jU2QUsgnmNMdCpdQ6+xbxO7jahPOqVcgmT5NmFVPcGcomypNTRvFkhlR9rl11T+L8dfPz0/hr371yTZZV/H7kv//EiUqFVhfrMWoAi1ZAWMYt0Rees1ylb+LzYEY3dqD4qvZPkmqdHRkolq2M4I8xEJx4yBctzRTn0ymyijEDhVXYpGCuzp6HHf5MiIFglRF83spGoIvmZvzrsY/UmHPNlwJFsXQ9dbM6Tou1jOBhK2OAY9zHWsWoT8EbK6OQTYBGDsa3iN1so+41NlZ7rNoEqZjzecOCBFP4auza1RiBYiGbKF5OBVWrjynd6NfcMQLFhc6M4uVWbM1tyxHoa9RZnDECxZcj/J2kJbcbAYGC0o1aFx46UJzr5KhCbhnFqwReA4xttB0Ihg4Uith16LpGIBWeu0ANRtuyZch++Dn71ZleTmspgg3wqERzU/Ro6Lc6ZEYhm6jLFE+NG5KsghqcjjH9P1SgWGZ44eA4uU0//ZDAa4ApDF6rGCpQyCbqk9tiMhkFtTgfujNxqEBhgV19TjLrcLvRJktFLoZ8q0MEisGjF9nILat4mcBrgCkMup5tiEBh2qlef8vsnVtPQS0GXYB3bItjM/3weqDXQn6aDff+lNmrvpMBU4nBWmWPzShkE3XL8RGcpp+oxelQ389jAsVCSywZngOmn6jJIDfzx0w9ncen2FG3ppvoQUZHYBGnn6AWfzr2uSzHZBR2iSVk+MD/e9NPVOboovahgWK0zafIUm7TT1ZpU5Ojp58ODRSK2LTZTRbSdfSN/aGBQhGbttyyy1uPSKUyR5UKDilmN0HihbOsOMfeZT/ObIuM0wQC3Jce9MWEji5q93EZ9/Y3yhpM78p3yJhwHLz/0yFTT6adAPJzcG25b6CwASBAng4uavcNFLltAgfAbw4qavcJFLbsAMjb2SGzQn0ChSABkLeDbvj7BArTTgD5613U7hooTDsBlKH3Y4y7BgpBAqAcvbKKroHCtBNAOXrd/MsoAOrTa01Fl0AhSACUp/Oaii6BwrQTQHk6JwEyCoA6Lbo+/W5foMjtMZcAdNdpxmhfoJBNAJSr05Ye+wKF+gRA2fYmBLsCxSJOPQFQrr0Jwa5AYdoJoHx7p592BYqHThCAKuxMDHYFirkfPA/ANHZOP20LFMu+uwsCkK2d00/bAoVsAqAuW6eftgUK9QmAumydfpJRABB2ZRS/3/B76hN1uhrpXf8QQvg2gyN65QYJ3gWLl+uHYVOg8GWp05ifew6BAvil7PBBoNg09fS5gwVQpY03jJsChYyCGjnvYcu2TZsChf2dAOr1wU3TeqBwV0WNnPfwm72BQjZBjTycC37zwTq69UBhoR01coMEv9mbUVg/wdBuMziiOv3gfe/dPJl6Ymw/Z3CE1SjgfVsDhS8LY7hO/Kgu1SjgA++VIdqBQjbBGFIPFG6Q4ENbM4pPHSwG9sFWAAnSwAEf2hooZBQM7YcMjqhnw8Nmv8YEgYKx3GeQUZyoT8BWGwOFLwxDehmDRcq+9InDVr8ul1gFCgU9hvY0gyN6nsBrgFT9Wr9bBQoL7RjS8wwW2p3LomGnDzIKgYIh5ZBNfJPAa4CUfRAotMYylKeZZBNujmC/d2UJGQVDagLEkwyOqGwCunkXGwQKhvRFBkfzifMdOhMoGFQz5XST+CFtzvOvEngdkIt3ZYmPBQkGcJ3JlNOlTifo5deMQqDgGDeZTDldWC8Evb1bnb3+PAroo1l5/TiDFdjNyf4sgdcBuXmXgX9sjycO1ASHRxnUJZoT/SqB1wG5Wv7enC0HepxRkFjE1t1mvGr9+nbLmo/VZoHLOD6NP3Obumo+n+/ia9cSzKHeBQro63Emz5o4iRfK656LAHcFwJM4HsbAkWKNr9lC5fvWQ6MWAgXHehFCeGsYHcadjfQ+sIzHpOmoej3jefRiz/5Vl85x48Dx5KOYmusGYZ9cahIpOI1Zx6fx53KEzOM+Zgw/dNzSvfn336R7yEjYU1NPdCFI9HO95Vnh67WP0Kp/7PMq/vebOI3W97O4jQHFE/3oTaBgn9U6idQ3+svB3IH2O4GCAzy04I5dXsZMQpAow7ZMB3YSKNjmacwkUl9MRz/fO1709VGsgsPKfUbtrxzmjRtEergWKGhTj6jDqp0XOhEoWHkeMwnKt4hZhV0Z6MSmgKz87EhU4z52QEEnMgpWmummzxyNaiziSnvYS0bBytL2HFW5j9ONsJeMgjZZRV1s60EnMgractxKm8PdaoOmC4GCdbajrouiNnuZemKTBzYArIodpNlJRsEmXzkqVbGtBzvJKNjmMyu0q2JbD7aSUbCNrKIuT2s/AGznCXdscx+zCrvH1uPOth5sIqNgm+aCceHoVEUHFJvcCxTs8qWjU5VvZZBscCNQsIttPepybwEem3zcemg7bGIBXl0UtfmAjIJ9lh7IX5VbmwWy5vZjc5J0oFW2Lhbg0fbzx7ZqoINTLdRVuY4D3jH1RFc6oOqiVZaVm4/iL2zjQRe29aiLbT1oPBIo6KMpcj6e4Ygt42gWAZ7s+HM3seZ2K6ANommNvizgfXCcz1aB4vWeLyCEibb1WNVDPh3gQUrXMXj8GH8teHR3HlujZRSs4sS7/Z7eGkaH8WTgr81p/DunOAffxDvkcxfArc7jcfJdMN7GJOJXTxwUo+O4G+BidBYv2HczH/fmgvgsvp6aN8NbxGuAAGGsj6v2iXLhABk9xiHbepwkEhx2jdfxgllLK3AOn4kx73hvBuHUh2H0GG96XIzO4wU4x+P7It5ElTRNtcj8MzGmHefrJ48PwOgzdm3rsdqivKSpjFV9I8dpqlVweOEcN3qOd9n1R62TSc80fTRdRI82/PmL2C1T+pz/ddxQM9VVzIsY1P5mry6O8C5GtAOFJ93R16PWRfIsFoZrvdlot+LezLA1zjLWHE5icNDuzrGac/hB83f8vvUXvRIo6OnLuDbh0rmzcT+sm3h8fmwtArw/MoictBYeNj8ftn4NQ/r1PG1nFGdxDhOYRtegsTQtzAy+jk89fC9QLHt2swBQrgerG5mP1t6igjYATbb7p9VRWN9m3LMpAHivk289UHh+NgDvxYL1QOGpVgC8FwvWaxQh7vui1Q6gTrfxcQK/2vQoVFkFQL0+iAGbAoU6BUC9flh/55umnqynAKjTe22xK5syCs8bBqjTy03velOgCNv+MABF+2DaaVegUKcAqMv9IRnFvZMEoBpbZ5K2BYpg+gmgKt9t+w+7AsXGuSoAinO7a6+/fRmF6SeA8j3d9Q53BYpg+gmgeFuL2Cv7AoXpJ4CyPd83e7RpZfY6DzMCKNdn+xZZ78soGrh6JdQAAAQkSURBVN87QQCK9LzLThxdMgp7PwGUaW82ETpmFLe2HgcoTqdsovG7ju/8HyGE/+I8ASjGF12XQHTJKEJsnbKjLEAZOmcToUdGEWJWceYkAchak0X8NYTwz65vomtGEfpGIACS9F3fXTf6ZBRBVgGQtdtYm+ilS3vsOgvwAPL06JAu1j5TTytfO0EAsvP80KUOh2QUjasQwqnzBCAL93Fx3UE7gh+SUTQe24IcIBtHXbP7FrNXmn/wP8kqAJL37a6n13Vx6NTTiikogHTdxAL2UTNAxwaKpvvpdQhh4UQBSMp9DBJbH3Ha1aE1ipXbOPcFQFq+HiJIhCNqFG0/xYziL04SgCQ0dYl/HeqFHDv11KZeATC/l4esvt5lyECxiMHixIkCMItBitfrhgwUIQaJK8VtgMk1NeMHY6xxO7aYvW6UaAbATvd9HkTU19AZxYrMAmAag7XBbjNWoAiCBcDoRg8SYeRAEQQLgNFMEiTCCDWKdTdTvRGAitxOeW0dO6NY0ToLMIzJm4bGzihW7mPb1vOJ/j2AEr2co7N0qkCx8tjeUAAH+XbMFthdppp6WtdMQb3w7G2Ave7jBn+zzcgMsSngIf5vCOH7EMIfbCYIsNVNzCL+Y85DNFegaPwzhPD3EMKPMVhooQX4TZNB/NfY4TSrOQPFyk+yC4Bf3ccA8a/xhnp2KQSK0MouXsX6xScJvCaAqb1Mce3Z1F1P+1zHNtrHNhYEKnIbaxGzdDXtk0pGsa6Jpv8zhPD/Yobxh7ReHsBgnsab42R3sJirPbaPpsh9EUL4SsEbKMh1DBCzF6v3ySFQrAgYQAmuYxZxnct7ySlQrDRB4jwGDAv2gFzcxoVzL3P7xHIMFG1NwPgyhHCazksCeE+TOXyXY4BYyT1QrCxjhnFuWgpIxMsYILKZYtqmlEDR1gSLv4UQztJ5SUAl7uOK6u9yKFJ3VWKgWFnEYCFoAGO7jjtMFPkohZIDRZugAQztJgaHlyVlD5vUEijaFrH4/bf4U+cU0FU1waGtxkCx7iQGjIfxp2I4sNLUHJpppR/iz2qCQ5tA8SGBA+p2HTcovS6hY2kIAsV+yxgwPm8FEaAMt3E66VX8KTBsIFAc5iSOz1u/lnlA2m7i+DkGhBu7VHcjUAxn2co+/iiAwGyuYwD4MQaD25R3Zs2BQDGNVa2jCRyftoKKjis4zGqK6FUMCquAUGWxeWwCxfyWa2OVjQQZCZW6b2UAr+LP67WfTEigyMOiFTzamcinrV+3/wykqH3H3wSCf8RfX7d+T80gQQJFudanttazk083TH3JYOijfXd/G4vEYS0juFcfyJ9AwT6bgse2+srDDb+nnTg9my7eq+Jv2/odvjv+SgkUzGFb5rKvwP95j4wn9eyoz0W3fbe+7b9vK+K6uHOcEML/B8ZzK5QBJyWQAAAAAElFTkSuQmCC" />
                                    <image id="Layer_1_copy" data-name="Layer 1 copy" x="2" y="2" width="394"
                                        height="512"
                                        xlink:href="data:img/png;base64,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" />
                                </svg>


                                <!-- <span class="vmh_cart_quantity"><?php echo getTotalCartQuantity() ?></span> -->
                                <!-- <h5><?php echo get_woocommerce_currency_symbol() ?>
                                    <span class="vmh_total_price"><?php echo getTotalCartPrice(); ?></span>
                                </h5> -->
                                <h5>
                                    <span><?php echo totalEarningOfUser(); ?></span>
                                </h5>
                            </a>
                            <?php }?>
                            <div class="shiping_icon_area">
                                <ul>
                                    <li>
                                        <a href="#">


                                            <a href="#" class="dark-mode-swtich">

                                                <div class="wp-dark-mode-switcher wp-dark-mode-ignore">

                                                    <label for="wp-dark-mode-switch"
                                                        class="wp-dark-mode-ignore wp-dark-mode-none">
                                                        <div class="modes wp-dark-mode-ignore">
                                                        </div>
                                                    </label>

                                                </div>

                                                <i class="fas fa-sun"></i>
                                            </a>

                                            <a href="#" class="vmh-serach-box" data-toggle="modal"
                                                data-target="#vmh_search_modal">
                                                <i class="fas fa-search"></i>
                                            </a>

                                            <!-- <a href="#" class="main_icon_rating_link">
                                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11 0C4.93461 0 0 4.93461 0 11C0 17.0654 4.93461 22 11 22C17.0654 22 22 17.0654 22 11C22 4.93461 17.0655 0 11 0ZM11 20.8108C8.30526 20.8108 5.86092 19.7185 4.08611 17.9537C3.37734 17.249 2.77568 16.4368 2.30721 15.5441C1.59368 14.1848 1.18921 12.6388 1.18921 11C1.18921 5.59031 5.59031 1.18921 11 1.18921C13.5659 1.18921 15.9045 2.17981 17.6546 3.79817C18.5619 4.63716 19.3112 5.64471 19.8515 6.77089C20.4661 8.05218 20.8108 9.48659 20.8108 11C20.8108 16.4097 16.4097 20.8108 11 20.8108Z"
                                                        fill="#FFF7EF" />
                                                    <path
                                                        d="M7.40771 9.73644C8.06449 9.73644 8.59692 9.20402 8.59692 8.54724C8.59692 7.89046 8.06449 7.35803 7.40771 7.35803C6.75093 7.35803 6.21851 7.89046 6.21851 8.54724C6.21851 9.20402 6.75093 9.73644 7.40771 9.73644Z"
                                                        fill="#FFF7EF" />
                                                    <path
                                                        d="M14.7659 9.73644C15.4227 9.73644 15.9551 9.20402 15.9551 8.54724C15.9551 7.89046 15.4227 7.35803 14.7659 7.35803C14.1091 7.35803 13.5767 7.89046 13.5767 8.54724C13.5767 9.20402 14.1091 9.73644 14.7659 9.73644Z"
                                                        fill="#FFF7EF" />
                                                    <path
                                                        d="M10.979 17.0945C13.2796 17.0945 15.4845 15.9179 16.7469 13.9644L15.748 13.319C14.5888 15.1128 12.4684 16.1139 10.3451 15.8689C8.69048 15.6781 7.16035 14.7248 6.25187 13.319L5.25308 13.9644C6.35235 15.6656 8.20499 16.8192 10.2088 17.0503C10.466 17.08 10.7229 17.0945 10.979 17.0945Z"
                                                        fill="#FFF7EF" />
                                                </svg>
                                            </a> -->

                                            <?php if (is_user_logged_in()) {?>
                                            <a
                                                href="<?php echo esc_url(get_permalink(get_option('vmh_create_product_option'))) ?>">
                                                <i class="fas fa-plus-circle"></i>
                                                <!-- <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="11" cy="11" r="10.5" stroke="white" />
                                                    <path
                                                        d="M11.8386 10.3711H14.1301V12.0278H11.8386V14.6177H10.093V12.0278H7.79517V10.3711H10.093V7.88916H11.8386V10.3711Z"
                                                        fill="white" />
                                                </svg> -->
                                            </a>

                                            <?php }?>

                                            <a href="<?php echo site_url('/cart') ?>" class="vmh_cart_total_wrapper">
                                                <svg width="22" height="22" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16ZM0 0V2H2L5.6 9.6L4.2 12C4.1 12.3 4 12.7 4 13C4 14.1 4.9 15 6 15H18V13H6.4C6.3 13 6.2 12.9 6.2 12.8V12.7L7.1 11H14.5C15.3 11 15.9 10.6 16.2 9.99996L19.8 3.5C20 3.3 20 3.2 20 3C20 2.4 19.6 2 19 2H4.2L3.3 0H0ZM16 16C14.9 16 14 16.9 14 18C14 19.1 14.9 20 16 20C17.1 20 18 19.1 18 18C18 16.9 17.1 16 16 16Z"
                                                        fill="#ffffff" />
                                                </svg>
                                                <span
                                                    class="vmh_cart_quantity"><?php echo getTotalCartQuantity() ?></span>
                                            </a>

                                        </a>
                                    </li>

                                    <li>
                                        <div class="dropdown vmh_profile_dropdown">
                                            <a class="btn btn-secondary dropdown-toggle vmh_profile" href="#"
                                                role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="fas fa-user-circle"></i>
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" href="<?php echo wp_logout_url() ?>">
                                                    <?php echo is_user_logged_in() ? 'Logout' : 'Login' ?>
                                                </a>
                                                <?php if (is_user_logged_in()) {?>
                                                <a class="dropdown-item vmh_your_profile_link"
                                                    href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>/edit-account/">Your
                                                    profile</a>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </li>
                                </ul>




                                <!-- Start Rating Popup -->
                                <div class="subscribe_mail_popup contact_rating_popup main_icon_ratine">
                                    <div class="subscribe_mail_popup_header contact_rating_popup_heade">
                                        <h3>Rate our service</h3>
                                    </div>
                                    <div class="star-rating">
                                        <input type="radio" id="5-stars" name="rating" value="5" />
                                        <label for="5-stars" class="star">&#9733;</label>
                                        <input type="radio" id="4-stars" name="rating" value="4" />
                                        <label for="4-stars" class="star">&#9733;</label>
                                        <input type="radio" id="3-stars" name="rating" value="3" />
                                        <label for="3-stars" class="star">&#9733;</label>
                                        <input type="radio" id="2-stars" name="rating" value="2" />
                                        <label for="2-stars" class="star">&#9733;</label>
                                        <input type="radio" id="1-star" name="rating" value="1" />
                                        <label for="1-star" class="star">&#9733;</label>
                                    </div>
                                    <div class="contact_popup_textarea">
                                        <textarea placeholder="Type your message (Optional)"></textarea>
                                    </div>
                                    <div
                                        class="logon_input_btn shipping_address_btn shipping_method_btn payment_btn rating_btn">
                                        <a href="#"><input type="submit" value="Submit" /></a>
                                    </div>
                                    <div class="subscribe_hide_icon home_page_subscribe_popup ">
                                        <a href="#"><img
                                                src="<?php echo VMH_URL . 'Assets/images/subscribe_hide.png' ?>"
                                                alt="images"></a>
                                    </div>
                                </div>

                                <!-- End Rating Popup -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </header>
    <!--End Header Area-->


    <!-- search Modal -->
    <div class="modal fade" id="vmh_search_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button type="button" class="close vmh_search_modal_close" data-dismiss="modal" aria-label="Close">
                    <!-- <span class="vmh_serach_close" aria-hidden="true"><i class="fas fa-times-circle"></i></span> -->
                    <span class="vmh_serach_close" aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-body">
                    <?php echo getSearchBar() ?>
                </div>
            </div>
        </div>
    </div>