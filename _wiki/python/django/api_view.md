## api view

### post method

```python
#!/usr/bin/env python2.7
# coding: utf-8

from django.contrib.auth.decorators import login_required
from django_external.django.decorators import api_view, post, get, json_params
from django_external.django.response import api_response
from django_external.django.validators import require_params

@api_view
@post
@login_required
def health_record(request):
    params = request.POST
    require_params(params, {
        'id': int,
        'env': unicode,
    })
    health_id = params['id']
    ServiceHealthyController.delete_health_record(health_id)
    return api_response({
        'status': 'true'
    })

@api_view
@post
@json_params
def pub_images(request):
    params = request.json
    require_params(params, {
        'service_id': unicode,
        'service_type': unicode,
    })
    result = UnitController.build(request.json) # dict struct
    return api_response(result)
```

### get method

```python
@api_view
@get
def get_service_health_data(request):
    check_point = request.GET.get('check_point', CheckPoint.PEAK)
    metrics_type = request.GET.get('metrics_type', None)
    alarm_level = request.GET.get('alarm_level', None)
    service_level = request.GET.get('service_level', None)
    reponse = ServiceHealthyController.query_list(check_point)
    return api_response(reponse)
```


## url map

```python
from cap.monitor import api_views
from django.conf.urls import url

urlpatterns = [
    url(r'^health/$', api_views.health_record, name='health_record'),
]
```

## generate class

```python
image = Image.objects.create(service_id=img_data['service_id'],
                                     env=img_data['env'],
                                     base_image=img_data['base_image'],
                                     user_id=img_data['user_id'])
```


## ref

```python
def post(function):
  def _decorated(request, *args, **kwargs):
    if request.method != 'POST':
      raise BadRequest('The request view must be POST.')
    return function(request, *args, **kwargs)
  return _decorated


def get(function):
  def _decorated(request, *args, **kwargs):
    if request.method != 'GET':
      raise BadRequest('The request view must be GET.')
    return function(request, *args, **kwargs)
  return _decorated
  
def json_params(function):
    def _decorated(request, *args, **kwargs):
        request.json = json.loads(request.body)
        return function(request, *args, **kwargs)
    return _decorated

def api_view(function):
  def _api_decorated_view(function, request, *args, **kwargs):
    try:
      return function(request, *args, **kwargs)
    except Exception as e:
      code = get_error_code(e)
      if code / 100 == 5:
        print traceback.format_exc()
      return api_response_error(error=unicode(e.message) or unicode(e),
                                status_code=code)

  @csrf_exempt
  def _decorated(request, *args, **kwargs):
    return _api_decorated_view(function, request, *args, **kwargs)
  return _decorated
```