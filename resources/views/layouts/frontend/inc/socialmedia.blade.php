<div class="social-media">
    <ul class="social-media-icon">
        @if (!empty(SettingHelper::setting('facebook')))
            <li>
                <a class="" href="{!! SettingHelper::setting('facebook') !!}">
                    <i class="fab fa-facebook-f"></i>
                </a>
            </li>
        @endif
        @if (!empty(SettingHelper::setting('twitter')))
            <li>
                <a class="" href="{!! SettingHelper::setting('twitter') !!}">
                    <i class="fab fa-twitter"></i>
                </a>
            </li>
        @endif
        @if (!empty(SettingHelper::setting('instagram')))
            <li>
                <a class="" href="{!! SettingHelper::setting('instagram') !!}">
                    <i class="fab fa-instagram"></i>
                </a>
            </li>
        @endif
    </ul>
</div>
