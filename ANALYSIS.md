# Dokuwiki Commonmark 플러그인 프로젝트 분석 보고서

## 1. 프로젝트 목적

이 프로젝트는 Dokuwiki에서 Commonmark(마크다운) 구문을 파싱하여 Dokuwiki 구문으로 렌더링하는 Dokuwiki 플러그인입니다. 사용자는 `<!DOCTYPE markdown>` 지시어를 사용하여 특정 페이지에서 마크다운을 활성화하거나, 구성에서 `force_commonmark` 옵션을 통해 모든 페이지에 대해 강제로 활성화할 수 있습니다.

## 2. 아키텍처 및 핵심 기술

플러그인은 Dokuwiki의 이벤트 후크 시스템, 특히 `PARSER_WIKITEXT_PREPROCESS` 이벤트를 사용하여 Dokuwiki의 자체 파서가 처리하기 전에 마크다운 콘텐츠를 가로챕니다.

핵심 기술 스택은 다음과 같습니다.

*   **PHP:** 플러그인이 작성된 언어입니다.
*   **Dokuwiki 플러그인 API:** Dokuwiki의 핵심 기능과 통합하는 데 사용됩니다.
*   **league/commonmark:** 마크다운 텍스트를 추상 구문 트리(AST)로 파싱하는 데 사용되는 강력한 PHP 라이브러리입니다.
*   **Composer:** `league/commonmark`와 같은 PHP 종속성을 관리하는 데 사용됩니다.

## 3. 코드 구조

코드는 논리적인 디렉터리와 파일로 잘 구성되어 있습니다.

*   `action.php`: Dokuwiki 이벤트 시스템에 연결되고 파싱 프로세스를 트리거하는 메인 플러그인 파일입니다.
*   `composer.json`: `league/commonmark` 및 `symfony/yaml`과 같은 프로젝트 종속성을 정의합니다.
*   `src/`: 플러그인의 핵심 로직이 포함된 메인 소스 코드 디렉터리입니다.
    *   `bootstrap.php`: Composer의 autoloader를 로드합니다.
    *   `Dokuwiki/Plugin/Commonmark/`:
        *   `Commonmark.php`: 파싱 및 렌더링 프로세스를 총괄하는 메인 클래스입니다. 사용자 정의 확장으로 Commonmark 환경을 설정하고 프론트매터 및 위키링크와 같은 특정 기능을 처리합니다.
        *   `DWRenderer.php`: 개별 노드의 렌더링을 특정 렌더러 클래스에 위임하는 메인 렌더러 클래스입니다.
        *   `Extension/`: `league/commonmark`의 사용자 정의 확장을 포함합니다.
            *   `Renderer/`:
                *   `Block/`: 제목, 목록, 인용문과 같은 블록 수준 마크다운 요소에 대한 렌더러 클래스를 포함합니다.
                *   `Inline/`: 굵은 텍스트, 링크, 이미지와 같은 인라인 수준 마크다운 요소에 대한 렌더러 클래스를 포함합니다.

## 4. 작동 방식

1.  `action.php`의 `_commonmarkparse` 함수가 `PARSER_WIKITEXT_PREPROCESS` 이벤트 중에 트리거됩니다.
2.  이 함수는 페이지 콘텐츠에 마크다운 지시어가 있는지 확인하거나 `force_commonmark` 구성이 활성화되어 있는지 확인합니다.
3.  조건이 충족되면 콘텐츠가 `Commonmark::RendtoDW` 메서드에 전달됩니다.
4.  `Commonmark.php`는 사용자 정의 확장(예: 테이블, 취소선)으로 `league/commonmark` 환경을 설정합니다.
5.  텍스트는 `league/commonmark`를 사용하여 AST로 파싱됩니다.
6.  `DWRenderer.php`는 AST를 순회하고 각 노드 유형에 대해 `Extension/Renderer/` 디렉터리에 있는 해당 사용자 정의 렌더러를 호출합니다.
7.  각 렌더러는 특정 AST 노드를 Dokuwiki 구문 문자열로 변환합니다.
8.  최종 렌더링된 Dokuwiki 텍스트는 Dokuwiki의 파서로 다시 전달되어 추가 처리를 거칩니다.
