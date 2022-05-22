﻿// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.Threading.Tasks;
using GeniusProtocol;
using Grpc.Core;
using Microsoft.Extensions.Logging;

namespace Genius.OAuth.Services;

public class GrpcOAuthService : GeniusProtocol.OAuth.OAuthBase
{
    private readonly ILogger<GrpcOAuthService> _logger;

    public GrpcOAuthService(ILogger<GrpcOAuthService> logger)
    {
        _logger = logger;
    }

    public override Task<TokenModel> GetAccessToken(AccessTokenLookup request, ServerCallContext context)
    {
        return base.GetAccessToken(request, context);
    }

    public override Task<TokenModel> GetLoginToken(LoginTokenLookup request, ServerCallContext context)
    {
        return base.GetLoginToken(request, context);
    }

    public override Task<TokenResponseModel> ValidateToken(TokenModel request, ServerCallContext context)
    {
        return base.ValidateToken(request, context);
    }
}
