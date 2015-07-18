<!--Created by PhpStorm.-->
<!--User: rikazdev-->
<!--Date: 3/8/15-->
<!--Time: 6:05 AM-->

<!doctype html>
<html>
<head>
</head>

<body>
<div style="margin-left: 50px; margin-right: 50px">
<div align="center" class="text-warning"><h1> Client Health Summary </h1></div>
<br/>
<br/>

<div class="alert alert-info">
    <p>
        Nutritious foods and an active lifestyle are important to live a healthy life and reduce your risk of
        developing
        diseases.
        This personalised report is designed to assist you to make positive decisions along your health journey
        with the
        support of
        your nutrition professional as required.
    </p>

    <p>
        The recommendations are based on the information that you have provided and are designed for individuals
        that are
        generally healthy,
        not pregnant or breastfeeding and are over 18 years of age. The report is based on medical based
        research.
    </p>
</div>
<br/>

<div>
    <h2 class="text-primary">Weight and Waist Measurements</h2>
    <br/>
    <ol>
        <li>
            Your current weight is: {{ $weight }}
        </li>
        <li>
            Your height is: {{ $height }}
        </li>
        <li>
            Your BMI is: {{ $BMI }}
        </li>
        <li>
            Your weight falls into the {{ $weight_category }}
        </li>

    </ol>
    <br/>

    <div class="alert alert-info">
        <p>
            Your BMI (body mass index) is a measure of your weight in relation to your height and is only a guide to
            your weight category. Generally, being within a healthy weight range can lower your risk of developing many
            chronic diseases, but factors such as your age, ethnicity and weight history should be taken into account
            when determining your healthy goal weight. Your nutrition professional can assist you to set a practical
            weight goal.

        </p>
    </div>
    <br/>
    <ol>
        <li>
            Your waist measurement is: {{ $waist }}
        </li>
        <li>
            Your waist falls into the {{ $waist_category }}
        </li>

    </ol>
    <br/>

    <div class="alert alert-info">
        <p>

            Your waist measurement gives more information on your shape. Generally, the greater your waist, the greater
            your health risk. <strong class="text-success"> Your nutrition professional </strong> can support you during
            your health journey
            goals with the <strong class="text-info"> Health Tracker. </strong>

        </p>
    </div>
    <br/>
    <ol>
        <li>
            Your estimated energy requirement is {{ $estimated_energy_requirment }} based on your ideal body weight with
            a BMI of 23.5***
        </li>
        @if ( !strcmp($weightGoal,'maintain') )
        <li>
            As your weight goal is to <strong> maintain </strong> your weight then you need to choose ameal plan that
            has a similar energy to your estimated requirements
        </li>
        @elseif( !strcmp($weightGoal,'lose') )

        <li>
            As your weight goal is to <strong> lose </strong> weight then you need to choose a meal plan with an energy
            level lower than your estimated energy requirements.
        </li>
        @elseif( !strcmp($weightGoal,'gain') )

        <li>
            As your weight goal is to <strong> gain </strong> weight then you need to choose a meal plan with an energy
            level greater than your estimated requirement.
        </li>
        @endif
    </ol>
    <br/>

    <div class="alert alert-info">
        <p>
            Your estimated energy requirements provided is a guide only and your nutrition professional can assist you
            with more detailed information.
        </p>
    </div>

</div>

<div>
<h2 class="text-primary"> Dietary Recommendations </h2>
<br/>

<div class="alert alert-info">
    <p>
        Your diet is your body’s fuel and the quality of this fuel is vital for health. Despite debate as to what
        makes up an ideal diet, nutrition professionals generally agree that your eating plan should include a wide
        variety of nutritious foods from all the food groups and within an energy budget to achieve your health and
        weight goals.
    </p>
</div>
<br/>

<div class="alert alert-info">
    <p>
        This report will give you personal recommendations on how you can improve your diet and achieve your health
        and weight goals in line with evidence based research.
    </p>
</div>
<ul>
    @if ( !strcmp($fruitPerDay,'yes') )
    <li>
        You stated that you “do” have at least 2 serves of fruit per day.
    </li>
    @elseif( !strcmp($fruitPerDay,'no') )

    <li>
        You stated that you “do not” have at least 2 serves of fruit per day.
    </li>
    @elseif( !strcmp($fruitPerDay,'don\'t know') )

    <li>
        You stated that you “don’t know” whether you have at least 2 serves of fruit per day.
    </li>
    @endif

</ul>

<ul>
    @if ( !strcmp($vegetablePerDay,'yes') )
    <li>
        You stated that you “do” have at least 5 serves of vegetables each day.
    </li>
    @elseif( !strcmp($vegetablePerDay,'no') )

    <li>
        You stated that you “do not” have at least 5 serves of vegetables each day
    </li>
    @elseif( !strcmp($vegetablePerDay,'don\'t know') )

    <li>
        You stated that you “don’t know” whether you have at least 5 serves of vegetable each days.
    </li>
    @endif
</ul>

<br/>

<div class="alert alert-info">
    <p>
        Fruit and vegetables provide you with many important nutrients that can reduce your risk of developing chronic
        diseases. At least 2 serves of fruit and 5 serves of vegetables, especially green, orange, red vegetable and
        leafy vegetables are recommended for a healthy diet.
    </p>
</div>
<ul>
    @if ( !strcmp($meatPerDay,'yes') )
    <li>
        You stated that you “do” have at least 1 serve of meat per day.
        You are in line with the dietary guidelines as lean meats and poultry, fish, eggs, nuts and seeds and
        legumes/beans provide many important nutrients. However,a high consumption of red meat or processed meats may be
        associated with colorectal cancer.

    </li>
    @elseif( !strcmp($meatPerDay,'no') )

    <li>
        You stated that you “do not” have at least of 1 serve of meat per day.
        Lean meats and poultry, fish, eggs, nuts and seeds and legumes/beans provide many important nutrients.
        Insufficient intake of this food group may put you at risk of nutrient deficiencies. If you are vegetarian, you
        can still meet your nutritional requirements from alternatives to animal products. For further information, seek
        advice from your nutrition profession.

    </li>
    @elseif( !strcmp($meatPerDay,'don\'t know') )

    <li>
        You stated that you “don’t know” whether you have least 1 serve of meat per day.
        The dietary guidelines recommend at least 1 serve of lean meats and poultry, fish, eggs, nuts and seeds
        and
        legumes/beans daily as they provide many important nutrients. Insufficient intake of this food group may put
        you
        at risk of nutrient deficiencies. If you are vegetarian, you can still meet your nutritional requirements
        from
        alternatives to animal products. However, a high consumption of red meat or processed meats may be
        associated
        with colorectal cancer.
        For further information, seek advice from your nutrition profession.

    </li>
    @endif
</ul>

<ul>
    @if ( !strcmp($fishPerDay,'yes') )
    <li>
        You stated that you “do” have at least 2 serves of fish per week.
        You are in line with the dietary guidelines. Fish, especially from fatty fish such as mackerel, salmon or
        tuna can reduce your risk of heart disease, stroke, dementia and macular degeneration of the eyes.

    </li>
    @elseif( !strcmp($fishPerDay,'no') )
    <li>
        You stated that you “don’t” have at least 2 serves of fish per week.
        The dietary guidelines recommend that have at least 2 serves of fatty fish per week which may help to reduce
        your risk of heart disease, stroke, dementia and macular degeneration of the eyes. For more information, seek
        advice from your nutrition professional.
    </li>
    @elseif( !strcmp(fishPerDay,'don\'t know') )

    <li>
        You stated that you “don’t know” whether you have at least 2 serves of fish per week.

        <p>
            a: The dietary guidelines recommend that at least 2 serves of fatty fish per week may help to reduce your
            risk
            of heart disease, stroke, dementia and macular degeneration of the eyes. For more information, seek advice
            from
            your nutrition professional.
        </p>
    </li>
    @endif
</ul>


<ul>
    @if ( !strcmp($breadPerDay,'wholemeal') )
    <li>
        You stated that you prefer “wholemeal” bread.
        a: Wholemeal bread is higher in fibre than white bread, but not the lowest in glycaemic index. Wholemeal is
        faster to digest and has a higher GI than wholegrain. If you need to eat foods that have a lower glycaemic
        index, you could consider wholegrain breads. For more information, seek advice from your nutrition professional.

    </li>
    @elseif( !strcmp($breadPerDay,'wholegrain') )

    <li>
        You stated that you prefer “wholegrain” bread.
        a: Wholegrain breads are higher in fibre than white bread. Because the seeds and grains take longer to digest in
        wholegrain breads they have a lower glycaemic index than wholemeal and white breads. For more information, seek
        advice from your nutrition professional.

    </li>
    @elseif( !strcmp($breadPerDay,'other types') )

    <li>
        You stated that you prefer “white” bread.
        a: White bread contains less fibre than wholemeal and wholegrain, and has a higher glycaemic index. Choose
        wholegrain or wholemeal varieties where possible. For more information, seek advice from your nutrition
        professional.

    </li>
    @elseif( !strcmp($breadPerDay,'don\'t like') )

    <li>
        You stated that you prefer “other types” of bread.
        a: There are many types of breads available on the market. Choose wholegrain or wholemeal varieties where
        possible. For more information, seek advice from your nutrition professional.

    </li>
    @elseif( !strcmp($breadPerDay,'white') )
    <li>

        You stated that you “don’t like” bread.
        Bread provides many healthy nutrients, such as fibre and vitamin B. If you don’t like or are unable to
        tolerate bread, you can still obtain your nutrient requirements from other sources of wholegrains and
        cereals.
        In some cases, gluten free breads are better tolerated. Choose wholegrain or wholemeal varieties where
        possible.
        For more information, seek advice from your nutrition professional.


    </li>
    @endif
</ul>


<ul>

    @if ( !strcmp($dairyPerDay,'less than 2') )
    <li>
        You stated that you have “less than 2” serves of dairy per day.
        The dietary guidelines recommend at least 21/2 serves of milk, yoghurt and cheese per day. These are rich
        sources of calcium and other nutrients. Dairy foods are also shown to reduce our risk of many chronic diseases.
        If you need to lose weight, low fat varieties are a better option. For more information, seek advice from your
        nutrition professional.

    </li>
    @elseif( !strcmp($dairyPerDay,'at least 2 serves') )

    <li>
        You stated that you have “at least 2 serves”dairy per day.
        You are in line with the dietary guidelines. Milk, yoghurt and cheese per are rich sources of calcium and
        other nutrients. Dairy foods are also shown to reduce our risk of many chronic diseases. If you need to lose
        weight, low fat varieties are a better option. For more information, seek advice from your nutrition
        professional.

    </li>
    @elseif( !strcmp($dairyPerDay,'don\'t know') )
    <li>
        You stated that you “don’t know” how many serves of dairy per day.

        The dietary guidelines recommend at least 21/2 serves of milk, yoghurt and cheese per day. These are rich
        sources of calcium and other nutrients. Dairy foods are also shown to reduce our risk of many chronic
        diseases.
        If you need to lose weight, low fat varieties are a better option. For more information, seek advice from
        your
        nutrition professional.

    </li>
    @endif

</ul>

<ul>
    @if ( !strcmp($fattyFoodsPerDay,'yes') )
    <li>
        You stated that you “do” eat fried takeaways, cakes, sweet biscuits, chocolate or fatty snacks daily.
        These foods are not necessary for a healthy diet and are too high in unhealthy fats and/or added sugars
        and/or
        salt. They are high in energy and can contribute to weight problems, which are associated with chronic diseases.
        Replacing processed products containing unhealthy fats with more natural foods is a good way to limit your
        intake of
        these foods. For more information on unhealthy and healthy fats, seek advice from your nutrition professional.
    </li>
    @elseif( !strcmp($fattyFoodsPerDay,'no') )
    <li>
        You stated that you “do not” eat fried takeaways, cakes, sweet biscuits, chocolate or fatty snacks daily.
        You are in line with the dietary guidelines which state that these foods are optional extras to a healthy
        diet
        but are not necessary as they are too high in unhealthy fats and/or added sugars and/or salt.

    </li>
    @elseif( !strcmp($fattyFoodsPerDay,'don\'t know') )
    <li>
        You stated that you “didn’t know” whether you ate fried takeaways, cakes, sweet biscuits, chocolate or fatty
        snacks
        daily.
        These foods are not necessary for a healthy diet and are too high in unhealthy fats and/or added sugars
        and/or
        salt. They are high in energy and can contribute to weight problems, which are associated with chronic
        diseases.
        Replacing processed products containing unhealthy fats with more natural foods is a good way to limit your
        intake of
        these foods. For more information on unhealthy and healthy fats, seek advice from your nutrition
        professional.

    </li>
    @endif
</ul>


<ul>
    @if ( !strcmp($sugarFoodOrDrink,'yes') )
    <li>
        You stated that you “do” add sugar to your food and drinks.
        An intake of foods with added sugar should be limited. These foods are a source of excess energy resulting in
        obesity and increased risk of tooth decay. For more information on reducing added sugars in your diet, seek
        advice
        from your nutrition professional.

    </li>
    @elseif( !strcmp($sugarFoodOrDrink,'no') )
    <li>
        You stated that you “do not” add sugar to your food and drinks.
        This is in line with the dietary guidelines that state that added sugars in the diet are optional extras and
        their intake should be limited.


    </li>
    @elseif( !strcmp($sugarFoodOrDrink,'don\'t know') )
    <li>
        You stated that you “did not know” whether you added sugar to food and drinks.

        An intake of foods with added sugar should be limited. These foods are a source of excess energy
        resulting in
        obesity and increased risk of tooth decay. For more information on reducing added sugars in your diet, seek
        advice
        from your nutrition professional.

    </li>
    @endif
</ul>

<ul>
    @if ( !strcmp($sugarSoftDrink,'yes') )
    <li>
        You stated that you “do” have soft drink, fruit juice, cordials and lollies daily.
        The dietary guidelines recommend intake of sugary drinks and lollies should be limited. Sugar containing
        drinks
        include vitamin waters, energy and sports drinks. These foods are a source of excess energy resulting in obesity
        and
        increased risk of tooth decay. For more information on reducing added sugars in your diet, seek advice from your
        nutrition professional.

    </li>
    @elseif( !strcmp($sugarSoftDrink,'no') )
    <li>
        You stated that you “do not” have soft drink, fruit juice, cordials and lollies daily.
        This is in line with the dietary guidelines which recommend intake of sugary drinks and lollies should be
        limited.

    </li>
    @elseif( !strcmp($sugarSoftDrink,'don\'t know') )
    <li>
        You stated that you “do not know” whether you have soft drink, fruit juice, cordials or lollies daily.

        The dietary guidelines recommend intake of sugary drinks and lollies should be limited. Sugar containing
        drinks
        include vitamin waters, energy and sports drinks. These foods are a source of excess energy resulting in
        obesity and
        increased risk of tooth decay. For more information on reducing added sugars in your diet, seek advice from
        your
        nutrition professional.

    </li>
    @endif
</ul>


<ul>
    @if ( !strcmp($saltOnTable,'yes') )
    <li>

        You stated that you “do” add salt on the table without tasting your food first.
        The dietary guidelines state that foods with added salt should be limited. Excess salt intake is associated
        with
        high blood pressure. Processed foods are the major source of salt in Western diets. You can reduce your salt
        intake
        by reducing your intake of processed foods and not adding salt in cooking or at the table. Herbs and spices can
        be
        used as alternatives to salt, to enhance the flavour of foods. For more information on reducing your salt intake
        including how to read labels, seek advice from your nutrition professional.

    </li>
    @elseif( !strcmp($saltOnTable,'no') )
    <li>
        You stated that you “do not” add salt on the table without tasting your food first.
        The dietary guidelines state that foods with added salt should be limited. Excess salt intake is associated
        with
        high blood pressure. Although adding salt at the table contributes to your salt intake, it is the salt in
        processed
        foods that are the major source of salt in Western diets. You can further reduce your salt intake by reducing
        your
        intake of processed foods and not adding salt in cooking or at the table. Herbs and spices can be used as
        alternatives to salt, to enhance the flavour of foods. For more information on reducing your salt intake
        including
        how to read labels, seek advice from your nutrition professional.

    </li>
    @elseif( !strcmp($saltOnTable,'don\'t know') )
    <li>
        You stated that you “do not know” whether you add salt on the table without tasting your food first.

        The dietary guidelines state that foods with added salt should be limited. Excess salt intake is
        associated with
        high blood pressure. Processed foods are the major source of salt in Western diets. You can reduce your salt
        intake
        by reducing your intake of processed foods and not adding salt in cooking or at the table. Herbs and spices
        can be
        used as alternatives to salt, to enhance the flavour of foods. For more information on reducing your salt
        intake
        including how to read labels, seek advice from your nutrition professional.

    </li>
    @endif
</ul>

<ul>
    @if ( !strcmp($saltOnTable,'yes') )
    <li>

        You stated that you “do” add salt on the table without tasting your food first.
        The dietary guidelines state that foods with added salt should be limited. Excess salt intake is associated
        with
        high blood pressure. Processed foods are the major source of salt in Western diets. You can reduce your salt
        intake
        by reducing your intake of processed foods and not adding salt in cooking or at the table. Herbs and spices can
        be
        used as alternatives to salt, to enhance the flavour of foods. For more information on reducing your salt intake
        including how to read labels, seek advice from your nutrition professional.

    </li>
    @elseif( !strcmp($saltOnTable,'no') )
    <li>
        You stated that you “do not” add salt on the table without tasting your food first.
        The dietary guidelines state that foods with added salt should be limited. Excess salt intake is associated
        with
        high blood pressure. Although adding salt at the table contributes to your salt intake, it is the salt in
        processed
        foods that are the major source of salt in Western diets. You can further reduce your salt intake by reducing
        your
        intake of processed foods and not adding salt in cooking or at the table. Herbs and spices can be used as
        alternatives to salt, to enhance the flavour of foods. For more information on reducing your salt intake
        including
        how to read labels, seek advice from your nutrition professional.


    </li>
    @elseif( !strcmp($saltOnTable,'don\'t know') )
    <li>
        You stated that you “do not know” whether you add salt on the table without tasting your food first.


        The dietary guidelines state that foods with added salt should be limited. Excess salt intake is
        associated with
        high blood pressure. Processed foods are the major source of salt in Western diets. You can reduce your salt
        intake
        by reducing your intake of processed foods and not adding salt in cooking or at the table. Herbs and spices
        can be
        used as alternatives to salt, to enhance the flavour of foods. For more information on reducing your salt
        intake
        including how to read labels, seek advice from your nutrition professional.

    </li>
    @endif
</ul>


<ul>
    @if ( !strcmp($alcoholHowOften,'no') )
    <li>

        You stated that you “do not” drink alcohol.
        Alcohol is a route cause of many social and health problems. By not drinking, you are reducing your risk of
        some
        serious health problems.

    </li>
    @elseif( !strcmp($alcoholHowOften,'less than 5') )
    <li>

        You stated that you drink alcohol “less than 5” days per week.
        The frequency of your alcohol intake is in line with the NHMRC recommendations. The NHMRC* recommends that
        adults
        have at least 1 or 2 alcohol free days per week and should stick to no more than 2 standard drinks on any one
        day.

    </li>
    @elseif( !strcmp($alcoholHowOften,'at least 5') )
    <li>
        You stated that you drink alcohol “at least 5” days per week.
        The frequency of your alcohol intake is in line with the NHMRC recommendations. The NHMRC* recommends that
        adults
        have at least 1 or 2 alcohol free days per week and should stick to no more than 2 standard drinks on any one
        day.
        For more information on alcohol intake within healthy limits, contact your Medical Professional.

    </li>
    @elseif( !strcmp($alcoholHowOften,'don\'t know') )
    <li>
        You stated that you “do not know” how many days per week you drink alcohol.


        Alcohol is a route cause of many social and health problems. By not drinking, you are reducing your risk
        of some
        serious health problems.


    </li>
    @endif
</ul>

<ul>
    @if ( !strcmp($alcoholHowOften,'no') )
    <li>

        You stated that you “do not” drink alcohol.
        Alcohol is a route cause of many social and health problems. By not drinking, you are reducing your risk of
        some
        serious health problems.

    </li>
    @elseif( !strcmp($alcoholHowOften,'less than 5') )
    <li>

        You stated that you drink alcohol “less than 5” days per week.
        The frequency of your alcohol intake is in line with the NHMRC recommendations. The NHMRC* recommends that
        adults
        have at least 1 or 2 alcohol free days per week and should stick to no more than 2 standard drinks on any one
        day.

    </li>
    @elseif( !strcmp($alcoholHowOften,'at least 5') )
    <li>
        You stated that you drink alcohol “at least 5” days per week.
        The frequency of your alcohol intake is in line with the NHMRC recommendations. The NHMRC* recommends that
        adults
        have at least 1 or 2 alcohol free days per week and should stick to no more than 2 standard drinks on any one
        day.
        For more information on alcohol intake within healthy limits, contact your Medical Professional.

    </li>
    @elseif( !strcmp($alcoholHowOften,'don\'t know') )
    <li>
        You stated that you “do not know” how many days per week you drink alcohol.


        Alcohol is a route cause of many social and health problems. By not drinking, you are reducing your risk
        of some
        serious health problems.

    </li>
    @endif
</ul>

<ul>

    @if ( !strcmp($alcoholStandardDrinking,'have less than 2') )
    <li>
        You stated that you “have less than 2” standard drinks at a time.
        The NHMRC recommends that adults have no more than 2 standard drinks per day. A standard drink is:
        i. 375ml bottle of can of mid-strength beer (3.5% alcohol volume)
        ii. 100ml of wine (1 small glass often less that what is served at bars and restaurants)
        iii. 30ml or 1 nip of spirits.

    </li>
    @elseif( !strcmp($alcoholStandardDrinking,'3-4') )
    <li>
        You stated that you have “3-4”standard drinks at a time.
        The NHMRC states that drinking no more than 4 standard drinks at a time reduces the risk of alcohol related
        injury. However, the recommendation is for no more than 2 standard drinks per day. A standard drink is:
        i. 375ml bottle of can of mid-strength beer (3.5% alcohol volume)
        ii. 100ml of wine (1 small glass often less that what is served at bars and restaurants)
        iii. 30ml or 1 nip of spirits.
        Alcohol is often a source of hidden energy and therefore limiting alcohol is an important strategy for
        maintaining a
        healthy body weight.
        Seek help from a licensed professional if you are having trouble controlling your intake of alcohol.


    </li>
    @elseif( !strcmp($alcoholStandardDrinking,'over 4') )
    <li>
        You stated that you “over 4” standard drinks at a time.
        The NHMRC recommends that adults have no more than 2 standard drinks per day. Binge drinking is a major cause
        of
        many social and health problems.
        standard drink is:
        i. 375ml bottle of can of mid-strength beer (3.5% alcohol volume)
        ii. 100ml of wine (1 small glass often less that what is served at bars and restaurants)
        iii. 30ml or 1 nip of spirits.
        Alcohol is often a source of hidden energy and therefore limiting alcohol is an important strategy for
        maintaining a
        healthy body weight. Seek help from a licensed professional if you are having trouble controlling your intake of
        alcohol.


    </li>
    @elseif( !strcmp($alcoholStandardDrinking,'don\'t drink') )
    <li>
        You stated that you “don’t drink” alcohol

        Alcohol is a route cause of many social and health problems. By not drinking, you are reducing your risk
        of some
        serious health problems.

    </li>
    @endif
</ul>


<ul>
    @if ( !strcmp($alcoholStandardDrinking,'dark') )
    <li>
        You stated that the usual colour of your urine is “dark” yellow.
        The colour of your urine can be early indicator of dehydration. If your urine is dark yellow, it may be a
        sign
        that you are dehydrated. Water is the best, cheapest and safest drink. Water provides the fluid we need without
        the
        added energy. Seek advice from your nutrition professional to ensure that your fluid intake is adequate.

    </li>
    @elseif( !strcmp($alcoholStandardDrinking,'medium') )
    <li>
        You stated that the usual colour of your urine is “medium” yellow.
        The colour of your urine is an indicator of dehydration. Darker urine may be a sign that you are dehydrated.
        Water is the best, cheapest and safest drink. Water provides the fluid we need without the added energy. Seek
        advice
        from your nutrition professional to ensure that your fluid intake is adequate.

    </li>
    @elseif( !strcmp($alcoholStandardDrinking,'light') )
    <li>
        You stated that the usual colour of your urine is “light”.
        The colour of your urine is an indicator of dehydration. Light urine usually indicates good hydration. Water
        is
        the best, cheapest and safest drink. Water provides the fluid we need without the added energy. For more
        information
        on hydration, seek advice from your nutrition professional.

    </li>
    @elseif( !strcmp($alcoholStandardDrinking,'don\'t know') )
    <li>
        You stated that you “don’t know” the usual colour of your urine.


        The colour of your urine can be early indicator of dehydration. Darker urine may be a sign that you are
        dehydrated. Water is the best, cheapest and safest drink. Water provides the fluid we need without the added
        energy.
        Seek advice from your nutrition professional to ensure that your fluid intake is adequate.

    </li>
    @endif
</ul>

</div>
<br/>

<div>
<h2 class="text-primary"> Medical History </h2>
<br/>
@if ( !strcmp($diseaseDiabetic,'yes') )
<ul>
    <li>
        You stated that you have a medical history of “Diabetes”

        With diabetes the amount of glucose in the blood is high because the body is unable to process or use it
        effectively. This increases the risk of heart disease, stroke, and early death.
        To take dietary action against Diabetes:
        <ol>
            <li>Eat a healthy diet</li>
            <li>Maintain a healthy body weight</li>
            <li>Aim to exercise at least 3 times per week, although 5 times per week is preferable, but under
                medical
                supervision.
            </li>
        </ol>

        For dietary help controlling your diabetes, seek advice you’re your nutrition professional.

    </li>
</ul>
@endif


@if ( !strcmp($diseaseBloodPreasure,'yes') )
<ul>
    <li>
        You stated that you have a medical history of “High Blood Pressure”


        High blood pressure (hypertension) means that your blood is pumping at a higher pressure than normal through
        your arteries. This increases the risk of heart attack, kidney disease or stroke.

        <ol>
            <li>To take dietary action against high blood pressure</li>
            <li>Eat a healthy low salt diet</li>
            <li>Maintain a healthy body weight</li>
            <li>Exercise at least 3 days per week, but 5 days per week is preferable. Please seek advice from your
                medical
            </li>
        </ol>
        practitioner or exercise specialist to make sure that it is safe.
        For dietary help controlling your high blood pressure, seek advice from your nutrition professional.


    </li>
</ul>
@endif


@if ( !strcmp($heartDisease,'yes') )
<ul>
    <li>
        You stated that you have a medical history of Heart Disease.

        a. Heart disease occurs when the arteries that supply blood and oxygen to your heart become clogged with
        fatty
        material called plaque. This can lead to angina and heart attack.
        <ol>
            <li>To take dietary take action against heart disease</li>
            <li>Eat a healthy diet</li>
            <li>Maintain a healthy body weight</li>
            <li>Maintain an active lifestyle, but seek advice from your medical practitioner or exercise specialist
                to
                make
            </li>
        </ol>

        sure that it is safe.
        For dietary support for heart disease, seek advice from your nutrition professional.

    </li>
</ul>
@endif

@if ( !strcmp($cancer,'yes') )
<ul>
    <li>
        You sated that you have a medical history of Cancer.


        Cancer is a group of diseases involving abnormal cell growth with the potential to invade or spread to other
        parts of the body. The cause for many cancers is still unknown. Good nutrition is important for optimal health.
        Recommendations include:

        <ol>
            <li>A diet based on natural foods</li>
            <li>Limiting processed and red meats</li>
            <li>An active lifestyle</li>
        </ol>

        For more information on how to optimize your diet with cancer, seek advice from your nutrition professional.


    </li>
</ul>
@endif

@if ( !strcmp($kidney,'yes') )
<ul>
    <li>
        You stated that you have a medical history of Kidney Disease.


        Kidney disease occurs when the Nephrons inside your kidneys, which act as blood filters, are damaged. This
        leads to a build up of wastes and fluids inside your body. Good nutrition is important to optimize your kidney
        function.
        Recommendations include:
        <ol>
            <li>Eating a healthy low salt diet</li>
            <li>Maintaining a healthy body weight</li>
            <li>Staying fit and exercising regularly</li>
        </ol>

        For more information on how to optimize your diet with cancer, seek advice from your nutrition professional.

    </li>
</ul>
@endif

@if ( !strcmp($obesity,'yes') )
<ul>
    <li>
        You stated that you have a medical history of obesity.


        Obesity is a chronic disease where excess body fat accumulates causing a negative effect on health. This
        occurs most commonly due to an imbalanced between energy intake from food and drinks and the energy used by the
        body.
        Recommendations include:
        <ol>
            <li>Eating a healthy diet</li>
            <li>Eating less high energy foods and drinks</li>
            <li>Decreasing portion sizes</li>
            <li>Aim to exercise at least 3 times per week, although 5 times per week is preferable, but under medical
            </li>
        </ol>

        supervision.
        For dietary support for weight loss, seek advice from your nutrition professional.

    </li>
</ul>
@endif


@if ( !strcmp($cholesterol,'yes') )
<ul>
    <li>
        You stated that you have a medical history of high cholesterol


        Cholesterol is a fatty substance that is both produced by the body and found in some food. Too much
        cholesterol in the blood can block arteries and cause health problems.
        Recommendations include:
        <ol>
            <li>Eating a healthy diet</li>
            <li>Limiting foods that contain unhealthy fats</li>
            <li>Aim to exercise at least 3 times per week, although 5 times per week is preferable, but under medical
            </li>
        </ol>
        supervision.
        For dietary support for weight loss, seek advice from your nutrition professional.


    </li>
</ul>
@endif

@if ( !strcmp($thyroid,'yes') )
<ul>
    <li>
        You stated that you have a medical history of Thyroid Disease.


        Problems with your thyroid can result in an increase or decrease in your metabolic rate which influences your
        weight. Your medical practitioner will treat your thyroid with medication.
        For dietary management, the recommendations include:
        <ol>
            <li>Eating a healthy diet</li>
            <li>Maintain a healthy weight</li>
            <li>Aim to exercise at least 3 times per week, although 5 times per week is preferable, but under medical
            </li>
        </ol>

        supervision.
        For dietary support for thyroid, seek advice from your nutrition professional.


    </li>
</ul>
@endif

@if ( !strcmp($allergies,'yes') )
<ul>
    <li>
        You stated that you have a medical history of allergy.


        There are many types of food allergies and intolerances and many associated symptoms. Substances in foods can
        induce food intolerances and allergies. Generally, food intolerances do not involve the immune system and the
        symptoms are not as severe as in food allergies. Severe allergic reactions may require medical intervention.

        Diet can play a big role in reducing allergic reactions and symptoms of food intolerances. Your nutrition
        professional can work closely with you to minimize thesymptoms associated with your food allergies and
        intolerances.

    </li>
</ul>
@endif

</div>

<div>

    <h2 class="text-primary"> Exercise History </h2>


    <ul>
        @if ( !strcmp($exercisePerWeek,'do not') )
        <li>
            You stated that you “do not” exercise
            The physical activity guidelines* state that doing any physical activity is better than doing none.
            (Department of Health Guidelines). If you currently do no exercise, start slowly and gradually build up to
            the
            recommended amount. Your health professional can assist you with this.

        </li>
        @elseif( !strcmp($exercisePerWeek,'1-4') )
        <li>
            You stated that you exercise “1-4 “ days per week.
            The physical activity guidelines state that you should be active on most, preferably all days every week.
            Minimise the time spent on prolonged sitting or other sedentary activities where possible. Your health
            professional can assist you with this.

        </li>
        @elseif( !strcmp($exercisePerWeek,'5-7') )
        <li>
            You stated that you exercise “5-7” days per week.
            You are doing the recommended amount of physical activity as recommended by the guidelines. You should
            vary
            your exercise between moderate and vigorous activities and try to include muscle strengthening activities at
            least 2 days each week. Your health professional can assist you with this.

        </li>
        @elseif( !strcmp($exercisePerWeek,'don\'t know') )
        <li>
            You stated that you “did not know” how many days you exercised per week.

            <p>
                The physical activity guidelines state that you should be active on most, preferably all days every
                week.
                Minimise the time spent on prolonged sitting or other sedentary activities where possible. Your health
                professional can assist you with this.
            </p>
        </li>
        @endif
    </ul>

    <ul>
        @if ( !strcmp($exerciseLong,'less than 30') )
        <li>
            You stated that you exercise “less than 30” minutes per session.
            The guidelines state that you should be active, preferably all days per week. You should accumulate 2 ½ to 5
            hours of moderate intensity exercise and try to include some vigorous intensity exercise as well per week.
            Your
            health professional can assist you with this.

        </li>
        @elseif( !strcmp($exerciseLong,'30-60') )
        <li>
            You stated that you exercise “30-60” minutes per session.
            The guidelines state that you should be active, preferably all days per week. You should accumulate 2 ½ to 5
            hours of moderate intensity exercise and try to include some vigorous intensity exercise as well per week.
            Your
            health professional can assist you with this.

        </li>
        @elseif( !strcmp($routeParams,'over 60') )
        <li>
            You stated that you exercise for “over 60” minutes per session.
            The guidelines state that you should be active, preferably all days per week. You should accumulate 2 ½ to 5
            hours of moderate intensity exercise and try to include some vigorous intensity exercise as well per week.
            Your health professional can assist you with this.

        </li>
        @elseif( !strcmp($routeParams,'don\'t know') )
        <li>
            You stated that you “do not know: how long you exercise.


            The guidelines state that you should be active, preferably all days per week. You should accumulate 2 ½ to 5
            hours of moderate intensity exercise and try to include some vigorous intensity exercise as well per week.
            Your health professional can assist you with this.


        </li>
        @endif
    </ul>


</div>

<div>

    <h2 class="text-primary"> Family History </h2>

    <ul>
        @if ( !strcmp($familyDiseaseDiabetic,'yes') )
        <li>
            You stated that you “do” have a family history of diabetes
            Genetics may play a role in both type 1 and type 2 diabetes. Type 2 diabetes has a stronger link to family
            history than type 1. Studies show that it is possible to delay or prevent developing diabetes by exercising
            and
            maintaining a healthy weight range. For more detailed information on how to prevent developing diabetes,
            seek
            your nutrition professional.

        </li>
        @elseif( !strcmp($familyDiseaseDiabetic,'no') )
        <li>
            You stated that you “do not” have a family history of diabetes.
            Genetics may play a role in both type 1 and type 2 diabetes. Type 2 diabetes has a stronger link to family
            history than type 1. Studies show that it is possible to delay or prevent developing diabetes by exercising
            and
            maintaining a healthy weight range. For more detailed information on how to prevent developing diabetes,
            seek
            your nutrition professional.

        </li>
        @elseif( !strcmp($familyDiseaseDiabetic,'don\'t know') )
        <li>
            You stated that you “do not know” whether you have a family history of diabetes.


            Genetics may play a role in both type 1 and type 2 diabetes. Type 2 diabetes has a stronger link to family
            history than type 1. Studies show that it is possible to delay or prevent developing diabetes by exercising
            and
            maintaining a healthy weight range. For more detailed information on how to prevent developing diabetes,
            seek
            your nutrition professional.

        </li>
        @endif
    </ul>

    <ul>

        @if ( !strcmp($familyDiseaseHeart,'yes') )
        <li>
            You stated that you “do” have a family history of Heart disease.
            A family history of heart disease gives you an increased risk of developing associated diseases. You can
            reduce your risk of heart disease by having a healthy diet, being physically active and maintaining a
            healthy
            weight. For more detailed information on how to prevent developing heart disease, seek your nutrition
            professional.

        </li>
        @elseif( !strcmp($familyDiseaseHeart,'no') )
        <li>
            You stated that you “do not” have a family history of heart disease.
            A family history of heart disease gives you an increased risk of developing associated diseases. You can
            reduce your risk of heart disease by having a healthy diet, being physically active and maintaining a
            healthy
            weight. For more detailed information on how to prevent developing heart disease, seek your nutrition
            professional.

        </li>
        @elseif( !strcmp($familyDiseaseHeart,'don\'t know') )
        <li>
            You stated that you “do not know” whether you have a family history of heart disease.


            A family history of heart disease gives you an increased risk of developing associated diseases. You can
            reduce your risk of heart disease by having a healthy diet, being physically active and maintaining a
            healthy
            weight. For more detailed information on how to prevent developing heart disease, seek your nutrition
            professional.

        </li>
        @endif
    </ul>


</div>

<div>

    <h2 class="text-primary"> Readiness For Change </h2>

    @if ( !strcmp($readinessDiet,'have already') )
    <p>
        You stated that you “have already” changed your diet.
        It is important during this phase that you consolidate your gains to maintain changes to prevent relapses.
        Successful maintenance requires active attention to achieve long-term success. Seek advice from your nutrition
        professional to help with your maintenance phase.
    </p>
    @elseif( !strcmp($readinessDiet,'are not') )
    <p>
        You stated that you “are not” likely to change your diet.
        It is important to develop a firm detailed plan for action to carry you through behaviour changes. If you are
        not ready to take action yet, you can make progress by recognizing that there is a need for change. Seek advice
        from your nutrition professional to help you set goals that you are ready for.

    </p>
    @elseif( !strcmp($readinessDiet,'are likely') )
    <p>
        You stated that you “are likely” to change your diet in the future.

        To make changes in the future, it is important to develop a firm detailed plan for action to carry you
        through these behaviour changes. Preparation is an important step towards intending to change. Seek advice from
        your nutrition professional to help you set goals that you are ready for.

    </p>
    @endif

    @if ( !strcmp($readinessExercise,'have already') )
    <p>
        You stated that you “have already” changed your exercise habits.
        It is important during this phase that you consolidate your gains to maintain changes to prevent relapses.
        Successful maintenance requires active attention to achieve long-term success. Seek advice from your medical or
        exercise specialist to help with your maintenance phase.

    </p>
    @elseif( !strcmp($readinessExercise,'are not') )
    <p>
        You stated that you “are not” likely to change your exercise habits.
        It is important to develop a firm detailed plan for action to carry you through behaviour changes. If you are
        not ready to take action yet, you can make progress by recognizing that there is a need for change. Seek advice
        from your medical or exercise specialist to help you set goals that you are ready for.

    </p>
    @elseif( !strcmp($readinessExercise,'are likely') )
    <p>
        You stated that you “are likely” to change your exercise habits in the future.

    <p>
        To make changes in the future, it is important to develop a firm detailed plan for action to carry you
        through these behaviour changes. Preparation is an important step towards intending to change. Seek advice from
        your medical or exercise specialist to help you set goals that you are ready for.
    </p>
    </p>
    @endif

</div>

</div>

</body>
</html>
