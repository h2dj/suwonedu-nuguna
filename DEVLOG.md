# 개발 일지

## 2018/02/09 - 유지보수에 대한 단상.

다른 사람이 구축한 웹사이트, 코드를 관리하는 건 내키지 않고 어려운 일이다.
또 전임 유지보수 업체와 만나서 진행하는 인수인계 따위는 1도 없는 경우가 대부분이다.
형식적으로 영혼없이 말하는 이야기를 듣기보다는 웹사이트 개발 및 운영에 대한 구조화된 문서가 필요하다.
개발하며 차곡차곡 기록해놨더라면 그 내용이 실제 유지보수를 이어받게 되는 사람에게 훨씬 큰 도움이 될 것이다.

이 프로젝트도 절실하게 그런걸 느끼게 된다. 오죽하면 이렇게 개발 일지에 적고 앉아 있겠나? ㅎ
우리 사협은 힘들더라도 이렇게 기록히려고 노력이나 해봤으면 좋겠다.


## 2018/02/10 - 깔끔한 코드를 만들어보자!

기존 소스에 XE 최신 버전을 덮어써볼까 했지만 코어가 제공하는 기본 모듈, 위젯, 애드온, 레이아웃, 스킨 등과 구분이 되지 않아서
일단 최신 버전의 XE 소스에 기존 프로젝트에서 쓰던 모듈, 위젯 등을 역으로 추가해가며 커밋을 하고 
안전하다거나 믿을 수 있는 코드베이스를 만들어보자!

XE 릴리즈 주기를 대강 보면 한달에 버전 한개씩을 등록이 되는 듯 보여서 이걸 좀 편하게 하려면 어떻게 해야할까 고민이 좀 됐다.
xe 깃헙 레포지토리를 upstream으로 잡아서 버전을 찍어 머지하는 방식으로 할까도 생각했으나 너무 지저분해지는 듯 하여 포기...
컴포저 프로젝트로 구성하고 XE 코어와 각종 커뮤니티 제작 모듈, 위젯 등을 컴포저로 관리할 수 있다면 최고...였으나,
이런 레거시 프로젝트는 누구도 컴포저로 관리할 엄두를 못 내는 듯.... 결국 기존 방식대로 한땀한땀 코드 전체를 관리하는 방식으로 가자!

```
/addons
/layouts
/modules
/widgets
```

위의 4개 디랙토리에 추가한 사용하고 있는지도 정확히 파악이 안되는 기존 패키지들을 일일이 확인하며 하나씩 옮기며 순차적으로 커밋한다.
귀찮고 위험하며 지겹고.. 하여튼 권장할만한 방법은 아니다. 일단 시작은 이렇게... 누군가 더 좋은 방식을 찾아내면 개선해보자.

주의할 점은 회원 스킨과 보드 스킨도 추가하고 설정해야한다는... 
코드 커밋하기 전에 실제 관리자 화면에서 설정 값을 확인하고 해당하는 코드를 옮겨준다.
혹은 일단 가능한 범위에서 대강 확인하고 추가하고 오류가 발생하거나 하면 그때그때 대응한다.


## 2018/02/12 - 개발 스테이징용 서버를 만들자.

아 뭐 이렇게 까지 하냐 그러다가 제대로 해보자는 생각에 스테이징 서버를 만들기로...
수정하고 배포해서 학습관쪽에서 검토하고 이상이 없으면 실제 서버에 반영하는 방식으로 작업하기로 결정했다.
담당자들 깃헙 가입도 시키고 이슈도 깃헙을 통해 관리하는 방식으로 일해보려고 한다. 장문의 메일도 작성을....
젊은이들이 이런 걸 경험해봐야할텐데 다들 상황이 별로 관심이 없을 수 밖에 없는 듯 하여 좀 안타깝다만...
일단 계약한거니 진행하자...ㅎ


