<section class="pricing-plan bg-gredient3" id="pricing">
    <div class="container our-system">
        <div class="row">
            @foreach($plans as $plan)
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="plan-2">
                        <h6>{{$plan->name}}</h6>
                        <p class="price">
                            <small><h5>{{(env('CURRENCY_SYMBOL')) ? env('CURRENCY_SYMBOL') : '$'}}{{$plan->monthly_price}} Monthly Price</h5></small>
                        <small><h5>{{(env('CURRENCY_SYMBOL')) ? env('CURRENCY_SYMBOL') : '$'}}{{$plan->annual_price}} Annual Price</h5></small>
                        </p>
                        <p class="price-text">For companies that need a robust full-featured time tracker.</p>
                        <ul class="plan-detail">
                            <li>{{ ($plan->trial_days < 0)?__('Unlimited'):$plan->trial_days }} Trial Days</li>
                            <li>{{ ($plan->max_workspaces < 0)?__('Unlimited'):$plan->max_workspaces }} Workspaces</li>
                            <li>{{ ($plan->max_users < 0)?__('Unlimited'):$plan->max_users }} Users Per Workspace</li>
                            <li>{{ ($plan->max_clients < 0)?__('Unlimited'):$plan->max_clients }} Clients Per Workspace</li>
                            <li>{{ ($plan->max_projects < 0)?__('Unlimited'):$plan->max_projects }} Projects Per Workspace</li>
                        </ul>
                        <a href="{{route('register')}}" class="button">Active</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
