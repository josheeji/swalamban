  <!-- Title/Breadcrumb -->
  <section id="pagetitle" style="background-image:url({{isset($content->banner) ? asset('storage/' . $content->banner) : asset('swabalamban/images/titlebg.jpg') }});">
    <div class="container">
        <h1>{{@$content->title}}</h1>
        <ul>
            <li><a href="#!">Home</a><i class="fas fa-chevron-right"></i></li>
            <li>{{@$content->title}}</li>
        </ul>
    </div>
</section>
<!-- Title/Breadcrumb END -->
