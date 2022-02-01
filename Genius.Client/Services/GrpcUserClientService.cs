// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Client.Interfaces;
using GeniusProtocol;
using Microsoft.Extensions.Logging;

namespace Genius.Client.Services
{
    public class GrpcUserClientService
    {
        private readonly ILogger<GrpcUserClientService> _logger;

        private readonly User.UserClient _grpcClient;

        public GrpcUserClientService(ILogger<GrpcUserClientService> logger, IChannel channel)
        {
            _logger = logger;
            _grpcClient = new User.UserClient(channel.GetChannel());

            // TODO: To be implemented
        }
    }
}
